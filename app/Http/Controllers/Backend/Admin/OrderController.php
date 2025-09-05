<?php

namespace App\Http\Controllers\Backend\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::orderByDesc('created_at');

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
                    $info_btn = '<a href="'. route('admin.order.show', $order->id) .'" class="btn btn-sm btn-primary m-2"><i class="fa-solid fa-circle-info"></i></a>';
                    $invoice_generate_btn = $order->isConfirm() ? '<a href="#" class="btn btn-sm btn-dark m-2 generate-invoice" data-order-id="'. $order->id .'"><i class="fa-solid fa-file-invoice"></i></a>' : '';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light m-2 delete-btn" data-delete-url="' . route('admin.order.destroy', $order->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $info_btn . $invoice_generate_btn . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.admin.order.index');
    }

    public function show(Order $order)
    {
        return view('backend.admin.order.show', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Order $order)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $order->delete();

            return successMessage('Order deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }

    public function addToCartForm()
    {
        $categories = Category::get();
        return view('backend.admin.order.add_to_cart', compact('categories'));
    }

    public function getProductList(Request $request)
    {
        $products = Product::when($request->category_id, function($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->inStock()->paginate(9);

        $products = ProductResource::collection($products);

        $data = [
            'data' => $products,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ]
        ];

        return success( $data);
    }

    public function getAddToCartOrder()
    {
        $order = Order::with('order_items')
            ->pending()
            ->where('orderable_type', User::class)
            ->where('orderable_id', auth()->guard('web')->user()->id)
            ->first();

        $data = !is_null($order) ? new OrderResource($order) : [];

        return success($data);
    }

    public function addToCartOrderItems(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $auth_user = auth()->guard('web')->user();

            $product = Product::find($request->product_id);

            if (!$product) {
                throw new Exception('Product not found!');
            }

            $status = $request->status;

            $order = Order::where('orderable_type', User::class)->where('orderable_id', $auth_user->id)->pending()->first();

            if ($order) {
                $order_item = $order->order_items()->where('product_id', $product->id)->first();

                if ($order_item) {
                    if ($status == 'add') {
                        if ($product->isOutOfStock()) {
                            throw new Exception(translate('Out of stock!', 'ပစ္စည်းမရှိတော့ပါ!'));
                        }

                        $quantity = ($order_item->quantity ?? 0) + 1;
                        $price = $product->price * $quantity;

                        $order_item->quantity = $quantity;
                        $order_item->price = $price;

                        $product->decrement('stock_quantity');
                    } elseif ($status == 'remove') {
                        $quantity = ($order_item->quantity ?? 0) - 1;
                        $price = $quantity > 0 ? $order_item->price - $product->price : 0;

                        if ($quantity > 0 && $price > 0) {
                            $order_item->quantity = $quantity;
                            $order_item->price = $price;
                        } else {
                            $order_item->delete();
                            // $order->delete();
                        }

                        $product->increment('stock_quantity');
                    } else {
                        throw new Exception('Invalid status!');
                    }

                    $order_item->update();
                } else {
                    if ($status == 'add') {
                        $order_item = new OrderItem();
                        $order_item->order_id = $order->id;
                        $order_item->product_id = $product->id;
                        $order_item->quantity = 1;
                        $order_item->price = $product->price;
                        $order_item->save();

                        $product->decrement('stock_quantity');
                    }
                }

            } else {
                $order = new Order();
                $order->orderable_type = User::class;
                $order->orderable_id = $auth_user->id;
                $order->status = 'pending';
                $order->save();

                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->product_id = $product->id;
                $order_item->quantity = 1;
                $order_item->price = $product->price;
                $order_item->save();

                $product->decrement('stock_quantity');
            }

            DB::commit();
            return successMessage();

        } catch (Exception $e) {
            DB::rollback();
            return errorMessage($e->getMessage());
        }
    }

    public function orderConfirm(Request $request, $id)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $order = Order::where('orderable_type', User::class)
                ->where('orderable_id', auth()->guard('web')->user()->id)
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

            $order = Order::where('orderable_type', User::class)
                ->where('orderable_id', auth()->guard('web')->user()->id)
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
