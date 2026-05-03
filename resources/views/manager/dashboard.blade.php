@extends('layouts.app')

@section('title', 'Manager Dashboard | LEEMO-PALASADA')

@section('content')
    @include('partials.coreui-stat-cards', ['cards' => [
        ['label' => 'Active Vendors', 'value' => $stats['active_vendors'], 'color' => 'primary', 'icon' => 'cil-building', 'change' => '12.4%', 'trend' => 'up', 'progress' => 72],
        ['label' => 'Available Products', 'value' => $stats['available_products'], 'color' => 'info', 'icon' => 'cil-basket', 'change' => '8.1%', 'trend' => 'up', 'progress' => 60],
        ['label' => 'Monthly Sales', 'value' => 'PHP '.number_format($stats['monthly_sales'], 2), 'color' => 'warning', 'icon' => 'cil-chart-line', 'change' => '4.5%', 'trend' => 'up', 'progress' => 45],
        ['label' => 'Monthly Collections', 'value' => 'PHP '.number_format($stats['monthly_collections'], 2), 'color' => 'danger', 'icon' => 'cil-bank', 'change' => '3.8%', 'trend' => 'down', 'progress' => 38],
    ]])

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Market Overview</h4>
                    <div class="small text-body-secondary">Vendor activity and collection performance</div>
                </div>
                <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Dashboard range">
                    <div class="btn-group btn-group-toggle mx-3">
                        <input class="btn-check" id="range-day" type="radio" name="range" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="range-day">Day</label>
                        <input class="btn-check" id="range-month" type="radio" name="range" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary active" for="range-month">Month</label>
                        <input class="btn-check" id="range-year" type="radio" name="range" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="range-year">Year</label>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mt-4 text-center">
                <div class="col">
                    <div class="text-body-secondary">Active Vendors</div>
                    <div class="fw-semibold text-truncate">{{ $stats['active_vendors'] }} vendors</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 72%"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="text-body-secondary">Products</div>
                    <div class="fw-semibold text-truncate">{{ $stats['available_products'] }} available</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="text-body-secondary">Sales</div>
                    <div class="fw-semibold text-truncate">PHP {{ number_format($stats['monthly_sales'], 2) }}</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 45%"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="text-body-secondary">Collections</div>
                    <div class="fw-semibold text-truncate">PHP {{ number_format($stats['monthly_collections'], 2) }}</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 38%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Top Vendors</strong>
                    <a href="{{ route('manager.vendors.index') }}">View all</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach ($topVendors as $vendor)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                                    <div class="small text-body-secondary">Stall {{ $vendor->stall_number }}</div>
                                </div>
                                <strong>PHP {{ number_format($vendor->sales_total ?? 0, 2) }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><strong>Recent Collections</strong></div>
                <div class="card-body">
                    <table class="table table-hover align-middle mb-0">
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
                                    <td><span class="badge text-bg-success">{{ ucfirst($collection->status) }}</span></td>
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
