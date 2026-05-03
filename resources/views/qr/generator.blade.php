@extends('layouts.app')

@section('title', 'QR Generator | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">QR Generator</p>
            <h1 class="page-title mb-1">Download active platform QR codes</h1>
        </div>
        <a class="btn btn-outline-brand" href="{{ route('qr.scanner') }}">Open Scanner</a>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="content-card p-4 h-100">
                <p class="section-label">Product QR Codes</p>
                <h2 class="subheading mb-3">Product pages</h2>

                <div class="vstack gap-3">
                    @forelse($products as $product)
                        <div class="list-card">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <div class="fw-semibold">{{ $product->product_name }}</div>
                                    <small class="text-muted">{{ $product->vendor?->vendor_name }} | PHP {{ number_format($product->price, 2) }}</small>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-outline-brand" href="{{ route('qr.products.show', $product) }}">View</a>
                                    <a class="btn btn-sm btn-brand" href="{{ route('qr.products.download', $product) }}">Download</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No product QR codes are available for this account.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="content-card p-4 h-100">
                <p class="section-label">Stall QR Codes</p>
                <h2 class="subheading mb-3">Navigation locations</h2>

                <div class="vstack gap-3">
                    @forelse($marketMaps as $marketMap)
                        <div class="list-card">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <div class="fw-semibold">Stall {{ $marketMap->stall_number }}</div>
                                    <small class="text-muted">{{ $marketMap->vendor?->vendor_name ?? 'Vacant stall' }} | {{ $marketMap->zone_section }}</small>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-outline-brand" href="{{ route('qr.locations.show', $marketMap->qr_location_code) }}">View</a>
                                    <a class="btn btn-sm btn-brand" href="{{ route('qr.locations.download', $marketMap->qr_location_code) }}">Download</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No stall QR codes are available for this account.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
