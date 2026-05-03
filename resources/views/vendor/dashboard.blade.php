@extends('layouts.app')

@section('title', 'Vendor Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Vendor Workspace</p>
            <h1 class="page-title mb-1">{{ $vendor->vendor_name }}</h1>
            <p class="text-muted mb-0">Stall {{ $vendor->stall_number }} | Manage products and monitor sales.</p>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-brand">Add Product</a>
    </div>

    @include('partials.coreui-stat-cards', ['cards' => [
        ['label' => 'Daily Sales', 'value' => 'PHP '.number_format($stats['daily_sales'], 2), 'color' => 'primary', 'icon' => 'cil-cash', 'change' => '12.4%', 'trend' => 'up', 'progress' => 70],
        ['label' => 'Weekly Sales', 'value' => 'PHP '.number_format($stats['weekly_sales'], 2), 'color' => 'info', 'icon' => 'cil-chart-line', 'change' => '8.1%', 'trend' => 'up', 'progress' => 56],
        ['label' => 'Monthly Sales', 'value' => 'PHP '.number_format($stats['monthly_sales'], 2), 'color' => 'warning', 'icon' => 'cil-wallet', 'change' => '4.5%', 'trend' => 'up', 'progress' => 42],
        ['label' => 'Products Listed', 'value' => $stats['products'], 'color' => 'danger', 'icon' => 'cil-basket', 'change' => '3.8%', 'trend' => 'down', 'progress' => 34],
    ]])

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Top-selling products</h2>
                <div class="vstack gap-3">
                    @forelse ($topProducts as $productSales)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $productSales->product?->product_name ?? 'Unknown Product' }}</div>
                                <small class="text-muted">Units sold</small>
                            </div>
                            <strong>{{ $productSales->units_sold }}</strong>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Sales data will appear after your first transactions.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Recent sales</h2>
                <div class="table-responsive">
                    <table class="table table-theme align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSales as $sale)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($sale->transaction_date)->format('M d, Y h:i A') }}</td>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td><span class="badge-soft">{{ ucfirst($sale->payment_status) }}</span></td>
                                    <td>PHP {{ number_format($sale->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
