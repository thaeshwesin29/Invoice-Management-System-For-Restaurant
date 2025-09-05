<?php

namespace App\Http\Controllers\Backend\Admin;

use PDF;
use Exception;
use App\Models\User;
use App\Models\Staff;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::orderByDesc('created_at');

            return DataTables::of($invoices)
                ->addColumn('order_number', function ($invoice) {
                    return optional($invoice->order)->order_number;
                })
                ->addColumn('invoiceable', function ($invoice) {
                    $causer = '';
                    if (get_class($invoice->invoiceable) == User::class ) {
                        $causer = '(' . __('message.cashier') . ')';
                    } elseif (get_class($invoice->invoiceable) == Staff::class ) {
                        $causer = '(' . __('message.waiter') . ')';
                    }

                    return optional($invoice->invoiceable)->name . ' ' . $causer;
                })
                ->addColumn('total_amount', function ($invoice) {
                    return number_format($invoice->total_amount) . ' ' . __('message.mmk');
                })
                ->addColumn('tax', function ($invoice) {
                    return number_format($invoice->tax) . ' ' . __('message.mmk');
                })
                ->addColumn('action', function ($invoice) {
                    $info_btn = '<a href="'. route('admin.invoice.show', $invoice->id) .'" class="btn btn-sm btn-primary m-2"><i class="fa-solid fa-circle-info"></i></a>';
                    $invoice_download_btn = '<a href="'. route('admin.invoice.download', $invoice->id) .'" target="_black" class="btn btn-sm btn-dark m-2"><i class="fa-solid fa-file-arrow-down"></i></a>';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light m-2 delete-btn" data-delete-url="' . route('admin.invoice.destroy', $invoice->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $info_btn . $invoice_download_btn . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.admin.invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice = Invoice::with(['order' => function($query) {
            $query->with(['order_items' => function($q) {
                $q->with('product');
            }]);
        }])->find($invoice->id);

        return view('backend.admin.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            $invoice->delete();

            return successMessage('Invoice deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }

    public function downloadInvoice(Invoice $invoice)
    {
        $invoice = Invoice::with(['order' => function($query) {
            $query->with(['order_items' => function($q) {
                $q->with('product');
            }]);
        }])->find($invoice->id);

        if (!$invoice) {
            abort(404);
        }

        $data = [
            'title' => 'Invoice No. ' . $invoice->invoice_number,
            'invoice' => $invoice
        ];

        $pdf = PDF::loadView('pdf.invoice', $data);

        return $pdf->stream('invoice-' . $invoice->id . '-' . $invoice->invoice_number . '.pdf');
    }
}
