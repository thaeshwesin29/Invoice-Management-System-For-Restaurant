@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.waiter') }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
    <div class="container-lg px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="mb-4">
                    @include('components.back-button')
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-success text-light"><i class="fa-solid fa-plus"></i> {{ __('message.create_waiter') }}</a>
                </div>
                <div class="card">
                    <h5 class="card-header">{{ __('message.waiters') }}</h5>
                    <div class="card-body">
                        <table class="table" id="staffTable">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('message.name') }}</th>
                                    <th scope="col">{{ __('message.email') }}</th>
                                    <th scope="col">{{ __('message.phone') }}</th>
                                    <th scope="col">{{ __('message.profile_image') }}</th>
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
        var table = $('#staffTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            ajax: '{{ route("admin.staff.index") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'profile_image', name: 'profile_image' },
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
