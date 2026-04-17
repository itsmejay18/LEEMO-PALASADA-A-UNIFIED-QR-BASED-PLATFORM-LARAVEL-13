@extends('layouts.app')

@section('title', 'Collector Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Collector Workspace</p>
            <h1 class="page-title mb-1">Track and record market collections</h1>
        </div>
        <a href="{{ route('collector.collections.create') }}" class="btn btn-brand">Record Collection</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="stat-card"><span>Assigned Records</span><strong>{{ $stats['assigned_records'] }}</strong></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Monthly Total</span><strong>PHP {{ number_format($stats['monthly_total'], 2) }}</strong></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Pending Verification</span><strong>{{ $stats['pending_verification'] }}</strong></div></div>
    </div>

    <div class="content-card p-4">
        <h2 class="subheading mb-3">Recent collections</h2>
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $collection)
                        <tr>
                            <td>{{ $collection->vendor->vendor_name }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($collection->collection_date)->format('M d, Y') }}</td>
                            <td><span class="badge-soft">{{ ucfirst($collection->status) }}</span></td>
                            <td>PHP {{ number_format($collection->amount_collected, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
