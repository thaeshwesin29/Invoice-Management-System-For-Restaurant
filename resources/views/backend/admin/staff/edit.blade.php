@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">{{ __('message.waiter') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ __('message.edit_waiter') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.edit_waiter') }}</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')

                            @include('backend.admin.layouts.flash')

                            <div class="col-md-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $staff->name) }}">
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $staff->email) }}">
                            </div>
                            <div class="col-md-12">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="number" name="phone" class="form-control" id="phone" value="{{ old('phone', $staff->phone) }}">
                            </div>
                            <div class="col-md-12">
                                <label for="image" class="form-label">Profile Image</label>
                                <input class="form-control" type="file" name="profile_image" id="image">
                            </div>
                            <div class="col-md-12">
                                <div id="imagePreview" class="col-md-6" style="display: {{ !$staff->profile_image ? 'none' : '' }};">
                                    <p>Preview:</p>
                                    <img id="previewImage" src="{{ $staff->profile_image_url }}" alt="Image Preview" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="nrc" class="form-label">{{ __('message.nrc') }}</label>
                                <input type="text" name="nrc" class="form-control" id="nrc" value="{{ old('nrc', $staff->nrc) }}">
                            </div>
                            <div class="col-md-12">
                                <label for="address" class="form-label">{{ __('message.address') }}</label>
                                <textarea name="address" class="form-control" id="address" cols="30" rows="3">{{ old('address', $staff->address) }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $('#image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#previewImage').attr('src', e.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endsection
