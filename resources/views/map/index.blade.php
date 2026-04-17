@extends('layouts.guest')

@section('title', 'Market Map | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4 h-100">
                <p class="section-label">Navigation Search</p>
                <h1 class="page-title fs-3 mb-3">Find a stall or vendor</h1>

                <form method="GET" action="{{ route('map.index') }}" class="row g-3 mb-4">
                    <div class="col-12">
                        <input type="text" name="search" class="form-control" placeholder="Vendor, product, or stall" value="{{ $search }}">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Search</button>
                    </div>
                </form>

                @if ($selectedMap)
                    <div class="list-card mb-4">
                        <div class="fw-semibold">Selected Stall</div>
                        <p class="mb-0 text-muted">Stall {{ $selectedMap->stall_number }} | {{ $selectedMap->zone_section }} | Floor {{ $selectedMap->floor_level }}</p>
                    </div>
                @endif

                <div class="vstack gap-3">
                    @forelse ($searchResults as $vendor)
                        <div class="list-card">
                            <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                            <small class="text-muted d-block mb-2">Stall {{ $vendor->stall_number }}</small>
                            @if ($vendor->marketMap)
                                <a class="text-link" href="{{ route('map.index', ['target' => $vendor->marketMap->qr_location_code]) }}">Show on map</a>
                            @else
                                <a class="text-link" href="{{ route('vendors.show', $vendor) }}">Open vendor profile</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">Use the search box to find vendors by product or stall number.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label">Interactive Map</p>
                        <h2 class="subheading mb-0">Stall locations and route guidance</h2>
                    </div>
                </div>
                <div class="map-frame" data-map="leaflet" data-markers='@json($mapMarkers)' data-target-code="{{ $selectedMap?->qr_location_code }}" data-use-geolocation="true" data-entry-lat="14.59951200" data-entry-lng="120.98422200"></div>
                <p class="small text-muted mt-3 mb-0" data-route-summary>Directions will appear here when you select a target stall.</p>
            </div>
        </div>
    </div>
@endsection
