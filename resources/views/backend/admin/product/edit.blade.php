@extends('backend.admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">{{ __('message.product') }}</a></li>
        <li class="breadcrumb-item active"><span>{{ __('message.edit_product') }}</span></li>
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
                    <h5 class="card-header">{{ __('message.edit_product') }}</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.product.update', $product->id) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')

                            @include('backend.admin.layouts.flash')

                            <div class="col-md-12">
                                <label for="name" class="form-label">{{ __('message.category') }}</label>
                                <select class="form-select" name="category_id">
                                    <option value="">--- Please choose ---</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label">{{ __('message.name') }}</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $product->name }}">
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('message.description') }}</label>
                                    <textarea class="form-control" name="description" id="description" rows="3">{{ $product->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="image" class="form-label">{{ __('message.image') }}</label>
                                <input class="form-control" type="file" name="image" id="image">
                            </div>
                            <div class="col-md-12">
                                <div id="imagePreview" class="col-md-6" style="display: {{ !$product->image ? 'none' : '' }};">
                                    <p>Preview:</p>
                                    <img id="previewImage" src="{{ $product->image_url }}" alt="Image Preview" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">{{ __('message.price') }}</label>
                                <input type="number" name="price" class="form-control" id="price" value="{{ $product->price }}">
                            </div>
                            <div class="col-md-6">
                                <label for="stockQuantity" class="form-label">{{ __('message.stock_quantity') }}</label>
                                <input type="number" name="stock_quantity" class="form-control" id="stockQuantity" value="{{ $product->stock_quantity }}">
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Cancel</a>
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
