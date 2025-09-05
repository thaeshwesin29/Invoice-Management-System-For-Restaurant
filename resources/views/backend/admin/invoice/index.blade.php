@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.invoice') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.invoices') }}</h5>
                    <div class="card-body">
                        <table class="table" id="invoicesTable">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('message.invoice_number') }}</th>
                                    <th scope="col">{{ __('message.order_number') }}</th>
                                    <th scope="col">{{ __('message.invoiceable') }}</th>
                                    <th scope="col">{{ __('message.invoice_datetime') }}</th>
                                    <th scope="col">{{ __('message.total_amount') }}</th>
                                    <th scope="col">{{ __('message.tax') }}</th>
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
        var table = $('#invoicesTable').DataTable({
            ajax: '{{ route("admin.invoice.index") }}',
            columns: [
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'order_number', name: 'order_number' },
                { data: 'invoiceable', name: 'invoiceable' },
                { data: 'invoice_datetime', name: 'invoice_datetime' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'tax', name: 'tax' },
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
    });
    </script>
@endsection
