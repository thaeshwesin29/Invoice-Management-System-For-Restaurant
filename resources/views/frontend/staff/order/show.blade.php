@extends('frontend.staff.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">{{ __('message.order') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ $order->order_number }}</span></li>
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
                    <h5 class="card-header">{{ __('message.order_detail') }}</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.order_number') }}</label>
                                    <p>{{ $order->order_number }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.orderable') }}</label>
                                    <p>{{ optional($order->orderable)->name }} ({{ class_basename($order->orderable) }})</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.status') }}</label>
                                    <p>
                                        @if ($order->isPending())
                                            <span class="badge text-bg-warning">{{ ucfirst($order->status) }}</span>
                                        @elseif ($order->isConfirm())
                                            <span
                                                class="badge text-bg-success text-light">{{ ucfirst($order->status) }}</span>
                                        @elseif ($order->isCancel())
                                            <span
                                                class="badge text-bg-danger text-light">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.order_datetime') }}</label>
                                    <p>{{ $order->order_datetime }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.create_at') }}</label>
                                    <p>{{ $order->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.update_at') }}</label>
                                    <p>{{ $order->updated_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <h5 class="card-header">
                        <span>{{ __('message.order_items') }}</span>
                        @if ($order->isPending())
                        {{-- <button class="btn btn-danger btn-sm text-light float-end ms-1" type="button"><i class="fa-solid fa-xmark"></i> {{ __('message.order_cancel') }}</button>
                        <button class="btn btn-success btn-sm text-light float-end ms-1" type="button"><i class="fa-solid fa-check"></i> {{ __('message.order_confirm') }}</button> --}}
                        @elseif ($order->isConfirm())
                        <button class="btn btn-dark btn-sm float-end ms-1 generate-invoice" data-order-id="{{ $order->id }}" type="button"><i class="fas fa-file-invoice"></i> {{ __('message.generate_invoice') }}</button>
                        @endif
                    </h5>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 col-lg-8">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('message.no.') }}</th>
                                            <th scope="col">{{ __('message.item_name') }}</th>
                                            <th scope="col">{{ __('message.item_price') }}</th>
                                            <th scope="col">{{ __('message.quantity') }}</th>
                                            <th scope="col">{{ __('message.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_items as $key => $order_item)
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ $order_item->product->name }}</td>
                                                <td>{{ number_format($order_item->product->price) }} {{ __('message.mmk') }}</td>
                                                <td>{{ number_format($order_item->quantity) }}</td>
                                                <td>{{ number_format($order_item->price) }} {{ __('message.mmk') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="fw-medium text-center">{{ __('message.total_price') }}</td>
                                            <td class="fw-medium">{{ number_format($order->order_items->sum('quantity')) }}</td>
                                            <td class="fw-medium">{{ number_format($order->order_items->sum('price')) }} {{ __('message.mmk') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div>
                                    @php
                                        $order_items = $order->order_items;

                                        $total_product = $order_items->count();
                                        $total_price = $order_items->sum('price');
                                        $total_quantity = $order_items->sum('quantity');
                                        $tax = ($total_price*5)/100;
                                        $total_amount = number_format($total_price + $tax);
                                    @endphp
                                    <ol class="list-group total-cart-item-lists-area">
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
                                                <div>{{ $total_amount }} {{ __('message.mmk') }}</div>
                                            </div>
                                        </li>
                                    </ol>
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
            $(document).on('click', '.generate-invoice', function() {
                var order_id = $(this).data('order-id');

                CustomAlert.fire({
                        text: "{{ translate('Are you sure to generate invoice?', 'ပြေစာထုတ်ရန် သေချာပါသလား?') }}",
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            ProcessingAlert.fire({
                                text: "{{ translate('Generating..., Please wait!', 'ပြေစာထုတ်နေသည်..., ခဏစောင့်ပါ!') }}",
                            });

                            $.post(`/order/${order_id}/generate-invoice`,)
                                .then(function(res) {
                                    if (res.success) {
                                        setTimeout(() => {
                                            window.location.href = `/invoice/${res.data.invoice_id}`;
                                        }, 1000);
                                    } else {
                                        toastr.warning(res.message);
                                    }
                                }).fail(function(error) {
                                    toastr.error(error.message);
                                });
                        }
                    })
            });
        });
    </script>
@endsection
