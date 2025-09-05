@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.invoice.index') }}">{{ __('message.invoice') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ $invoice->invoice_number }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
    <div class="container-lg px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="mb-4">
                    @include('components.back-button')
                </div>
                <div class="card">
                    @php
                        $order = optional($invoice->order);
                        $order_items = $order->order_items;

                        $total_product = $order_items->count();
                        $total_price = $order_items->sum('price');
                        $total_quantity = $order_items->sum('quantity');
                        $tax = $invoice->tax;
                        $total_amount = $total_price + $tax;
                    @endphp
                    <h5 class="card-header">{{ __('message.invoice_detail') }}</h5>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-5">
                                <ol class="list-group mt-2">
                                    <li class="list-group-item align-items-start">
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.invoice_datetime') }}</div>
                                            <div>{{ $invoice->invoice_datetime }}</div>
                                        </div>
                                    </li>
                                </ol>
                                <ol class="list-group mt-2">
                                    <li class="list-group-item align-items-start">
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.invoice_number') }}</div>
                                            <div>{{ $invoice->invoice_number }}</div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.order_number') }}</div>
                                            <div>{{ $order->order_number }}</div>
                                        </div>
                                    </li>
                                </ol>
                                <ol class="list-group mt-2">
                                    @foreach ($order_items as $key => $order_item)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="w-1/2">
                                            <div>{{ $key + 1 }}. <span class="fw-bold">{{ $order_item->product->name }}</span></div>
                                        </div>
                                        <span class="">{{ number_format($order_item->quantity) }}</span>
                                        <span class="">{{ number_format($order_item->price) }} {{ __('message.mmk') }}</span>
                                    </li>
                                    @endforeach
                                </ol>
                                <ol class="list-group mt-2">
                                    <li class="list-group-item align-items-start">
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.total_product') }}</div>
                                            <div>{{ number_format($total_product) }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.total_quantity') }}</div>
                                            <div>{{ number_format($total_quantity) }}</div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.total_price') }}</div>
                                            <div>{{ number_format($total_price) }} {{ __('message.mmk') }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.tax') }}</div>
                                            <div>{{ number_format($tax) }} {{ __('message.mmk') }}</div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">{{ __('message.total_amount') }}</div>
                                            <div>{{ number_format($total_amount) }} {{ __('message.mmk') }}</div>
                                        </div>
                                    </li>
                                </ol>

                                <div class="d-grid my-3">
                                    <a href="{{ route('admin.invoice.download', $invoice->id) }}" target="_black" class="btn btn-dark" type="button"><i class="fa-solid fa-file-arrow-down"></i> {{ __('message.download_invoice') }}</a>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                let deleteUrl = $(this).data('delete-url');
                DeleteAlert.fire({
                    text: "{{ translate('Are you sure to delete?', 'ဖျက်ရန်သေချာပါသလား?') }}",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $.post(deleteUrl, {
                            '_method': 'DELETE'
                        })
                        .then(function(res) {
                            if (res.success == 1) {
                                table.ajax.reload();
                                toastr.success(res.message);
                            } else {
                                toastr.warning(res.message);
                            }
                        }).fail(function(error) {
                            toastr.error(res.message);
                        });
                    }
                })
            });
        });
    </script>
@endsection
