@extends('layouts.app')

@section('title', 'Edit Product | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Product Management</p>
        <h1 class="page-title mb-3">Update {{ $product->product_name }}</h1>
        @include('vendor.products._form', ['product' => $product])
    </div>
@endsection
