@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">{{ __('message.waiter') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ __('message.waiter_detail') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.waiter_detail') }}</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.name') }}</label>
                                    <p>{{ $staff->name }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.email') }}</label>
                                    <p>{{ $staff->email }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.phone') }}</label>
                                    <p>{{ $staff->phone }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.nrc') }}</label>
                                    <p>{{ $staff->nrc }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.address') }}</label>
                                    <p>{{ $staff->address }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.create_at') }}</label>
                                    <p>{{ $staff->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.update_at') }}</label>
                                    <p>{{ $staff->updated_at }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="fw-semibold">{{ __('message.profile_image') }}</label>
                                    <div>
                                        <img src="{{ $staff->profile_image_url }}" class="object-cover w-24 h-24"/>
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
