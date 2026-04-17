@extends('layouts.app')

@section('title', 'Customer Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="content-card p-4 h-100">
                <p class="section-label">Customer Dashboard</p>
                <h1 class="page-title mb-1">Welcome back, {{ auth()->user()->name }}</h1>
                <p class="text-muted mb-0">Review your orders, manage favorites, and scan QR codes for faster shopping.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="scanner-card h-100">
                <p class="section-label">Live QR Scanner</p>
                <div id="customer-qr-scanner" class="scanner-frame" data-qr-scanner data-resolve-endpoint="{{ route('api.scan.resolve') }}" data-result-target="#qr-status"></div>
                <p id="qr-status" class="small text-muted mb-0">Use your device camera to scan product or stall QR codes.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="stat-card"><span>Total Orders</span><strong>{{ $stats['orders'] }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Total Spent</span><strong>PHP {{ number_format($stats['spent'], 2) }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Bookmarks</span><strong>{{ $stats['bookmarks'] }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Cart Items</span><strong>{{ $stats['cart_items'] }}</strong></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="subheading mb-0">Recent transactions</h2>
                    <a href="{{ route('customer.transactions') }}" class="text-link">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-theme align-middle mb-0">
                        <thead><tr><th>Date</th><th>Vendor</th><th>Status</th><th>Total</th></tr></thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                                    <td>{{ $transaction->vendor->vendor_name }}</td>
                                    <td><span class="badge-soft">{{ ucfirst($transaction->payment_status) }}</span></td>
                                    <td>PHP {{ number_format($transaction->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="content-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="subheading mb-0">Favorite vendors</h2>
                    <a href="{{ route('customer.bookmarks') }}" class="text-link">Manage</a>
                </div>
                <div class="vstack gap-3">
                    @forelse ($favoriteVendors as $vendor)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                                <small class="text-muted">Stall {{ $vendor->stall_number }}</small>
                            </div>
                            <a href="{{ route('vendors.show', $vendor) }}" class="text-link">Open</a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Bookmark vendors from their profile pages to keep them here.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
