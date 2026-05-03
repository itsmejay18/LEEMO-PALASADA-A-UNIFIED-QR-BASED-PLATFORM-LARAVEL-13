@extends('layouts.app')

@section('title', 'Customer Dashboard | LEEMO-PALASADA')

@section('content')
    @include('partials.coreui-stat-cards', ['cards' => [
        ['label' => 'Total Orders', 'value' => $stats['orders'], 'color' => 'primary', 'icon' => 'cil-list-rich', 'change' => '12.4%', 'trend' => 'up', 'progress' => 70],
        ['label' => 'Total Spent', 'value' => 'PHP '.number_format($stats['spent'], 2), 'color' => 'info', 'icon' => 'cil-wallet', 'change' => '8.1%', 'trend' => 'up', 'progress' => 58],
        ['label' => 'Bookmarks', 'value' => $stats['bookmarks'], 'color' => 'warning', 'icon' => 'cil-bookmark', 'change' => '4.5%', 'trend' => 'up', 'progress' => 45],
        ['label' => 'Cart Items', 'value' => $stats['cart_items'], 'color' => 'danger', 'icon' => 'cil-cart', 'change' => '3.8%', 'trend' => 'down', 'progress' => 32],
    ]])

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
