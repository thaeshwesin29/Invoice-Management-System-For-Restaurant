@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.admin-user.index') }}">{{ __('message.cashier') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ __('message.cashier_detail') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.cashier_detail') }}</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.name') }}</label>
                                    <p>{{ $admin_user->name }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.email') }}</label>
                                    <p>{{ $admin_user->email }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.phone') }}</label>
                                    <p>{{ $admin_user->phone }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.create_at') }}</label>
                                    <p>{{ $admin_user->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.update_at') }}</label>
                                    <p>{{ $admin_user->updated_at }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.profile_image') }}</label>
                                    <div>
                                        <img src="{{ $admin_user->profile_image_url }}" class="object-cover w-24 h-24"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
