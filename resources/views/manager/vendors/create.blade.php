@extends('layouts.app')

@section('title', 'Add Vendor | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-4">
        <p class="section-label">Vendor Management</p>
        <h1 class="page-title mb-3">Add a new vendor record</h1>
        @include('manager.vendors._form')
    </div>
@endsection
