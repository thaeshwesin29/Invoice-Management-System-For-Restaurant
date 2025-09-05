<?php

namespace App\Http\Controllers\Frontend\Staff;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::where('orderable_type', Staff::class)->where('orderable_id', auth()->guard('staff')->user()->id)->orderByDesc('created_at');

            return DataTables::of($orders)
                ->addColumn('orderable', function ($order) {
                    $causer = '';
                    if (get_class($order->orderable) == User::class ) {
                        $causer = '(' . __('message.cashier') . ')';
                    } elseif (get_class($order->orderable) == Staff::class ) {
                        $causer = '(' . __('message.waiter') . ')';
                    }

                    return optional($order->orderable)->name . ' ' . $causer;
                })
                ->addColumn('status', function ($order) {
                    $status = '';
                    if ($order->isPending()) {
                        $status = '<span class="badge text-bg-warning">' . ucfirst($order->status) . '</span>';
                    } elseif ($order->isConfirm()) {
                        $status = '<span class="badge text-bg-success text-light">' . ucfirst($order->status) . '</span>';
                    } elseif ($order->isCancel()) {
                        $status = '<span class="badge text-bg-danger text-light">' . ucfirst($order->status) . '</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function ($order) {
                    $info_btn = '<a href="'. route('order.show', $order->id) .'" class="btn btn-sm btn-primary m-2"><i class="fa-solid fa-circle-info"></i></a>';
                    $invoice_generate_btn = $order->isConfirm() ? '<a href="#" class="btn btn-sm btn-dark m-2 generate-invoice" data-order-id="'. $order->id .'"><i class="fa-solid fa-file-invoice"></i></a>' : '';

                    return '<div class="flex justify-evenly">
                        ' . $info_btn . $invoice_generate_btn . '
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('frontend.staff.order.index');
    }

    public function orderConfirm(Request $request, $id)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $order = Order::where('orderable_type', Staff::class)
                ->where('orderable_id', auth()->guard('staff')->user()->id)
                ->pending()
                ->whereNull('order_datetime')
                ->find($id);

            if(!$order) {
                throw new Exception('Order not found!');
            }

            $order->status = 'confirm';
            $order->order_datetime = Carbon::now()->format('Y-m-d H:i:s');
            $order->update();

            return successMessage('Order confirmed successfully!');
        } catch (Exception $e) {
            return errorMessage($e->getMessage());
        }
    }

    public function orderCancel(Request $request, $id)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $order = Order::where('orderable_type', Staff::class)
                ->where('orderable_id', auth()->guard('staff')->user()->id)
                ->pending()
                ->whereNull('order_datetime')
                ->find($id);

            if(!$order) {
                throw new Exception('Order not found!');
            }

            if(isset($order->order_items)) {
                foreach ($order->order_items as $order_item) {
                    $order_item->product()->first()->increment('stock_quantity', $order_item->quantity);
                }
                $order->order_items()->delete();
            }

            $order->status = 'cancel';
            $order->update();

            return successMessage('Order canceled successfully!');
        } catch (Exception $e) {
            return errorMessage($e->getMessage());
        }
    }

    public function show(Order $order)
    {
        return view('frontend.staff.order.show', compact('order'));
    }

    public function generateInvoice(Order $order)
    {
        try {
            $order = Order::with('order_items')->find($order->id);

            $total_amount = $order->order_items->sum('price');
            $tax = ($total_amount*5)/100;

            $invoice = Invoice::firstOrCreate([
                'order_id' => $order->id,
                'invoiceable_type' => $order->orderable_type,
                'invoiceable_id' => $order->orderable_id
            ], [
                'invoice_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'total_amount' => $total_amount,
                'tax' => $tax
            ]);

            return success(['invoice_id' => $invoice->id]);

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }
}
