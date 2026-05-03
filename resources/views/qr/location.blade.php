@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')

@section('title', $marketMap->stall_number.' Navigation | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4 h-100">
                <p class="section-label">Stall Navigation QR</p>
                <img src="{{ route('qr.locations.image', $marketMap->qr_location_code) }}" alt="Location QR code" class="img-fluid qr-preview mb-3">
                <h1 class="page-title fs-3 mb-2">Stall {{ $marketMap->stall_number }}</h1>
                <p class="text-muted">Zone {{ $marketMap->zone_section }} | Floor {{ $marketMap->floor_level }}</p>
                @if ($marketMap->vendor)
                    <p class="fw-semibold mb-3">{{ $marketMap->vendor->vendor_name }}</p>
                @endif
                <div class="d-grid gap-2">
                    <a href="{{ route('qr.locations.download', $marketMap->qr_location_code) }}" class="btn btn-outline-brand">Download QR Code</a>
                    <a href="{{ route('map.index', ['target' => $marketMap->qr_location_code]) }}" class="btn btn-brand">Open Full Map</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label">Highlighted Route</p>
                        <h2 class="subheading mb-0">Interactive directions to your destination</h2>
                    </div>
                </div>
                <div class="map-frame" data-map="leaflet" data-markers='@json($mapMarkers)' data-target-code="{{ $marketMap->qr_location_code }}" data-use-geolocation="true" data-entry-lat="14.59951200" data-entry-lng="120.98422200"></div>
                <p class="small text-muted mt-3 mb-0" data-route-summary>We will draw a route to Stall {{ $marketMap->stall_number }} when the map finishes loading.</p>
            </div>
        </div>
    </div>
@endsection
