@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.order') }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
    <div class="container-lg px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="mb-4">
                    @include('components.back-button')
                    <a href="{{ route('admin.order.add-to-cart.form') }}" class="btn btn-success text-light"><i class="fa-solid fa-plus"></i> {{ __('message.create_a_order') }}</a>
                </div>
                <div class="card">
                    <h5 class="card-header">{{ __('message.orders') }}</h5>
                    <div class="card-body">
                        <table class="table" id="orderTable">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('message.order_number') }}</th>
                                    <th scope="col">{{ __('message.orderable') }}</th>
                                    <th scope="col">{{ __('message.status') }}</th>
                                    <th scope="col">{{ __('message.order_datetime') }}</th>
                                    <th scope="col">{{ __('message.create_at') }}</th>
                                    <th scope="col">{{ __('message.update_at') }}</th>
                                    <th scope="col">{{ __('message.action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#orderTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            ajax: '{{ route("admin.order.index") }}',
            columns: [
                { data: 'order_number', name: 'order_number' },
                { data: 'orderable', name: 'orderable' },
                { data: 'status', name: 'status' },
                { data: 'order_datetime', name: 'order_datetime' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

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

        $(document).on('click', '.generate-invoice', function(e) {
            e.preventDefault();
            var order_id = $(this).data('order-id');

            CustomAlert.fire({
                    text: "{{ translate('Are you sure to generate invoice?', 'ပြေစာထုတ်ရန် သေချာပါသလား?') }}",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        ProcessingAlert.fire({
                            text: "{{ translate('Generating..., Please wait!', 'ပြေစာထုတ်နေသည်..., ခဏစောင့်ပါ!') }}",
                        });

                        $.post(`/admin/order/${order_id}/generate-invoice`,)
                            .then(function(res) {
                                if (res.success) {
                                    setTimeout(() => {
                                        window.location.href = `/admin/invoice/${res.data.invoice_id}`;
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
