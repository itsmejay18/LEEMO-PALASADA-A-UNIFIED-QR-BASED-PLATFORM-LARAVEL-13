@php
    $editing = isset($product);
    $formId = $editing ? 'product_is_available_'.$product->id : 'product_is_available_new';
@endphp

<form method="POST" action="{{ $editing ? route('vendor.products.update', $product) : route('vendor.products.store') }}" class="row g-3">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" min="0" step="0.01" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" min="0" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" id="{{ $formId }}" @checked(old('is_available', $product->is_available ?? true))>
            <label class="form-check-label" for="{{ $formId }}">Product is available</label>
        </div>
    </div>

    <div class="col-12">
        <button class="btn btn-brand" type="submit">{{ $editing ? 'Update Product' : 'Create Product' }}</button>
    </div>
</form>
