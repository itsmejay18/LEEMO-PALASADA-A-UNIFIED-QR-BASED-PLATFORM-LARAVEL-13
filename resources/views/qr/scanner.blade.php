@extends('layouts.app')

@section('title', 'QR Scanner | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card p-4 h-100">
                <p class="section-label">QR Scanner</p>
                <h1 class="page-title fs-3 mb-3">Scan stall and product QR codes</h1>
                <div id="role-qr-scanner" class="scanner-frame mb-3" data-qr-scanner data-resolve-endpoint="{{ route('api.scan.resolve') }}" data-result-target="#scanner-status"></div>
                <p id="scanner-status" class="small text-muted mb-0">Camera scanner is ready when the browser grants access.</p>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card p-4 h-100">
                <p class="section-label">Workflow</p>
                <h2 class="subheading mb-3">{{ auth()->user()->primaryRole() ?? 'User' }} QR actions</h2>

                <div class="row g-3">
                    @if(auth()->user()->hasRole('Collector'))
                        <div class="col-md-6">
                            <a class="list-card d-block text-decoration-none h-100" href="{{ route('collector.collections.index') }}">
                                <div class="fw-semibold text-body">Record rental payment</div>
                                <p class="small text-muted mb-0">Open collection records after scanning a renter stall.</p>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a class="list-card d-block text-decoration-none h-100" href="{{ route('map.index', ['payment' => 'unpaid']) }}">
                                <div class="fw-semibold text-body">Prioritize unpaid stalls</div>
                                <p class="small text-muted mb-0">Open the payment compliance route map.</p>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('Treasurer'))
                        <div class="col-md-6">
                            <a class="list-card d-block text-decoration-none h-100" href="{{ route('treasurer.collections.index') }}">
                                <div class="fw-semibold text-body">Verify submitted collections</div>
                                <p class="small text-muted mb-0">Review forwarded collector payments.</p>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a class="list-card d-block text-decoration-none h-100" href="{{ route('map.index', ['payment' => 'unpaid']) }}">
                                <div class="fw-semibold text-body">Monitor unpaid renters</div>
                                <p class="small text-muted mb-0">Use red and green map markers for compliance.</p>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('Vendor'))
                        <div class="col-md-6">
                            <a class="list-card d-block text-decoration-none h-100" href="{{ route('vendor.products.index') }}">
                                <div class="fw-semibold text-body">Manage product QR codes</div>
                                <p class="small text-muted mb-0">Create products and download their generated QR codes.</p>
                            </a>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <a class="list-card d-block text-decoration-none h-100" href="{{ route('qr.generator') }}">
                            <div class="fw-semibold text-body">Open QR generator</div>
                            <p class="small text-muted mb-0">View and download available QR codes for your role.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
