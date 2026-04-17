@extends('layouts.app')

@section('title', 'Manager Reports | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Analytics Reports</p>
        <h1 class="page-title mb-1">Sales and collection performance</h1>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Daily sales</h2>
                <div class="table-responsive">
                    <table class="table table-theme align-middle mb-0">
                        <thead><tr><th>Date</th><th>Total Sales</th></tr></thead>
                        <tbody>
                            @foreach ($dailySales as $row)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
                                    <td>PHP {{ number_format($row->total_sales, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Collection summary</h2>
                <div class="vstack gap-3">
                    @foreach ($collectionSummary as $summary)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ ucfirst($summary->status) }}</div>
                                <small class="text-muted">{{ $summary->total_records }} records</small>
                            </div>
                            <strong>PHP {{ number_format($summary->total_amount ?? 0, 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="content-card p-4">
        <h2 class="subheading mb-3">Vendor performance snapshot</h2>
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Products</th>
                        <th>Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendorPerformance as $vendor)
                        <tr>
                            <td>{{ $vendor->vendor_name }}</td>
                            <td>{{ $vendor->products_count }}</td>
                            <td>PHP {{ number_format($vendor->sales_total ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
