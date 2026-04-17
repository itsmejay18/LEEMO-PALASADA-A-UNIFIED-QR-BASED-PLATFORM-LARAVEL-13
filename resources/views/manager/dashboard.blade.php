@extends('layouts.app')

@section('title', 'Manager Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Manager Workspace</p>
        <h1 class="page-title mb-1">Analytics, vendor oversight, and collection reporting</h1>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="stat-card"><span>Active Vendors</span><strong>{{ $stats['active_vendors'] }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Available Products</span><strong>{{ $stats['available_products'] }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Monthly Sales</span><strong>PHP {{ number_format($stats['monthly_sales'], 2) }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Monthly Collections</span><strong>PHP {{ number_format($stats['monthly_collections'], 2) }}</strong></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="subheading mb-0">Top vendors</h2>
                    <a href="{{ route('manager.vendors.index') }}" class="text-link">View all</a>
                </div>
                <div class="vstack gap-3">
                    @foreach ($topVendors as $vendor)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                                <small class="text-muted">Stall {{ $vendor->stall_number }}</small>
                            </div>
                            <strong>PHP {{ number_format($vendor->sales_total ?? 0, 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Recent collections</h2>
                <div class="table-responsive">
                    <table class="table table-theme align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Collector</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentCollections as $collection)
                                <tr>
                                    <td>{{ $collection->vendor->vendor_name }}</td>
                                    <td>{{ $collection->collector->name }}</td>
                                    <td><span class="badge-soft">{{ ucfirst($collection->status) }}</span></td>
                                    <td>PHP {{ number_format($collection->amount_collected, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
