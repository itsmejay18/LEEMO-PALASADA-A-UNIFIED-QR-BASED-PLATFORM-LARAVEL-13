@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')

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
                        <select name="occupancy" class="form-select">
                            <option value="">All stalls</option>
                            <option value="occupied" @selected(($filters['occupancy'] ?? '') === 'occupied')>Occupied stalls</option>
                            <option value="vacant" @selected(($filters['occupancy'] ?? '') === 'vacant')>Vacant stalls</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="payment" class="form-select">
                            <option value="">All payment statuses</option>
                            <option value="paid" @selected(($filters['payment'] ?? '') === 'paid')>Paid this month</option>
                            <option value="unpaid" @selected(($filters['payment'] ?? '') === 'unpaid')>Unpaid this month</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="contract" class="form-select">
                            <option value="">All contracts</option>
                            <option value="active" @selected(($filters['contract'] ?? '') === 'active')>Active contracts</option>
                            <option value="expiring" @selected(($filters['contract'] ?? '') === 'expiring')>Expiring in 30 days</option>
                            <option value="expired" @selected(($filters['contract'] ?? '') === 'expired')>Expired contracts</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="category" class="form-select">
                            <option value="">All vendor categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Search</button>
                    </div>
                    <div class="col-12">
                        <a class="btn btn-outline-brand w-100" href="{{ route('map.index') }}">Reset Filters</a>
                    </div>
                </form>

                <div class="row g-2 mb-4">
                    <div class="col-6"><div class="map-mini-stat"><span>Vacant</span><strong>{{ $mapStats['vacant'] }}</strong></div></div>
                    <div class="col-6"><div class="map-mini-stat"><span>Expiring</span><strong>{{ $mapStats['expiring'] }}</strong></div></div>
                    <div class="col-6"><div class="map-mini-stat text-success"><span>Paid</span><strong>{{ $mapStats['paid'] }}</strong></div></div>
                    <div class="col-6"><div class="map-mini-stat text-danger"><span>Unpaid</span><strong>{{ $mapStats['unpaid'] }}</strong></div></div>
                </div>

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
                <div class="mt-4">
                    <p class="section-label mb-2">Filtered Stalls</p>
                    <div class="vstack gap-2">
                        @forelse ($marketMaps->take(8) as $marketMap)
                            <a class="list-card text-decoration-none" href="{{ route('map.index', array_filter(array_merge(request()->query(), ['target' => $marketMap->qr_location_code]))) }}">
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-semibold text-body">Stall {{ $marketMap->stall_number }}</span>
                                    <span class="badge-soft">{{ $marketMap->vendor?->vendor_name ?? 'Vacant' }}</span>
                                </div>
                                <small class="text-muted">{{ $marketMap->zone_section }} | {{ $marketMap->vendor?->category ?? 'No category' }}</small>
                            </a>
                        @empty
                            <p class="text-muted mb-0">No stalls match the active filters.</p>
                        @endforelse
                    </div>
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
                <div class="map-legend mt-3">
                    <span><i class="map-dot paid"></i>Paid</span>
                    <span><i class="map-dot unpaid"></i>Unpaid</span>
                    <span><i class="map-dot vacant"></i>Vacant</span>
                    <span><i class="map-dot expiring"></i>Expiring contract</span>
                </div>
            </div>
        </div>
    </div>
@endsection
