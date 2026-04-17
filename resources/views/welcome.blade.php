@extends('layouts.guest')

@section('title', 'LEEMO-PALASADA')

@section('content')
    <section class="hero-panel mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <p class="section-label text-white-50">Unified QR Market Platform</p>
                <h1 class="display-5 fw-bold text-white mb-3">Navigate stalls, empower vendors, and simplify market operations with one QR-powered system.</h1>
                <p class="lead text-white-50 mb-4">LEEMO-PALASADA connects customers, vendors, collectors, managers, and treasurers through product QR codes, stall navigation, and responsive dashboards.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('map.index') }}" class="btn btn-light btn-lg">Open Market Map</a>
                    @auth
                        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="btn btn-outline-light btn-lg">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Create Customer Account</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-card p-4 bg-white">
                    <p class="section-label">Search the Market</p>
                    <form method="GET" action="{{ route('landing') }}" class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Search vendor, product, or stall number" value="{{ $search }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-brand w-100" type="submit">Search</button>
                        </div>
                    </form>

                    @if ($search !== '')
                        <div class="mt-4">
                            <h2 class="subheading mb-3">Search results</h2>
                            <div class="row g-3">
                                @forelse ($searchResults as $vendor)
                                    <div class="col-md-6">
                                        <div class="vendor-card h-100">
                                            <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                                            <small class="text-muted d-block mb-2">Stall {{ $vendor->stall_number }}</small>
                                            <a class="text-link" href="{{ route('vendors.show', $vendor) }}">Open vendor profile</a>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No matching vendors or products were found for "{{ $search }}".</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4 mb-5">
        <div class="col-lg-7">
            <div class="content-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label">Interactive Navigation</p>
                        <h2 class="subheading mb-0">Explore the public market map</h2>
                    </div>
                    <a href="{{ route('map.index') }}" class="text-link">Full map</a>
                </div>
                <div class="map-frame" data-map="leaflet" data-markers='@json($mapMarkers)' data-entry-lat="14.59951200" data-entry-lng="120.98422200"></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-card p-4 h-100">
                <p class="section-label">QR Workflows</p>
                <h2 class="subheading mb-3">How customers use the platform</h2>
                <div class="vstack gap-3">
                    <div class="list-card"><strong>1. Scan product QR</strong><p class="small text-muted mb-0">Open product details instantly, review availability, and add items to cart.</p></div>
                    <div class="list-card"><strong>2. Scan stall QR</strong><p class="small text-muted mb-0">Launch interactive stall navigation with highlighted locations on the map.</p></div>
                    <div class="list-card"><strong>3. Checkout with tracking</strong><p class="small text-muted mb-0">Simulate cash or QRPh payment and store transaction history in your dashboard.</p></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <p class="section-label">Featured Vendors</p>
                <h2 class="subheading mb-3">Trusted market stalls</h2>
                <div class="row g-3">
                    @foreach ($featuredVendors as $vendor)
                        <div class="col-md-6">
                            <div class="vendor-card h-100">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h3 class="h6 mb-1">{{ $vendor->vendor_name }}</h3>
                                        <small class="text-muted">Stall {{ $vendor->stall_number }}</small>
                                    </div>
                                    <span class="badge-soft">Active</span>
                                </div>
                                <div class="mt-3 d-flex gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-outline-brand" href="{{ route('vendors.show', $vendor) }}">Profile</a>
                                    @if ($vendor->marketMap)
                                        <a class="btn btn-sm btn-outline-brand" href="{{ route('qr.locations.show', $vendor->marketMap->qr_location_code) }}">Navigate</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <p class="section-label">Popular Picks</p>
                <h2 class="subheading mb-3">Sample products available today</h2>
                <div class="row g-3">
                    @foreach ($topProducts as $product)
                        <div class="col-md-6">
                            <div class="product-card h-100">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <h3 class="h6 mb-1">{{ $product->product_name }}</h3>
                                        <small class="text-muted">{{ $product->vendor->vendor_name }}</small>
                                    </div>
                                    <span class="badge-soft">PHP {{ number_format($product->price, 2) }}</span>
                                </div>
                                <a class="text-link" href="{{ route('qr.products.show', $product) }}">View product QR page</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
