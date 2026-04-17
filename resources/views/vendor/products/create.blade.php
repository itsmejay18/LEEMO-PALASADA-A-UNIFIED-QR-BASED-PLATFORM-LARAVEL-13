@extends('layouts.app')

@section('title', 'Add Product | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Product Management</p>
        <h1 class="page-title mb-3">Create a new product listing</h1>
        @include('vendor.products._form')
    </div>
@endsection
