@extends('layouts.app')

@section('title', 'Edit Vendor | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Vendor Management</p>
        <h1 class="page-title mb-3">Update {{ $vendor->vendor_name }}</h1>
        @include('manager.vendors._form', ['vendor' => $vendor])
    </div>
@endsection
