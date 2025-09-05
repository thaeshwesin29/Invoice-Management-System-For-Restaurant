@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.admin-user.index') }}">{{ __('message.cashier') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ __('message.change_cashier_password') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.change_cashier_password') }}</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.admin-user.update-password', $admin_user->id) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PATCH')

                            @include('backend.admin.layouts.flash')

                            <div class="col-md-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="col-md-12">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="confirmPassword">
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.admin-user.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
