@extends('layouts.app')

@section('title', 'Treasurer Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Treasury Workspace</p>
        <h1 class="page-title mb-1">Verify collections and issue receipts</h1>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="stat-card"><span>Pending Collections</span><strong>{{ $stats['pending_collections'] }}</strong></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Verified Today</span><strong>PHP {{ number_format($stats['verified_today'], 2) }}</strong></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Monthly Verified</span><strong>PHP {{ number_format($stats['monthly_verified'], 2) }}</strong></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Pending reviews</h2>
                <div class="vstack gap-3">
                    @foreach ($pendingCollections as $collection)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $collection->vendor->vendor_name }}</div>
                                <small class="text-muted">Submitted by {{ $collection->collector->name }}</small>
                            </div>
                            <a href="{{ route('treasurer.collections.verify', $collection) }}" class="btn btn-sm btn-outline-brand">Verify</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Recent receipts</h2>
                <div class="vstack gap-3">
                    @foreach ($recentRecords as $record)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $record->official_receipt_number }}</div>
                                <small class="text-muted">{{ $record->collection->vendor->vendor_name }}</small>
                            </div>
                            <a href="{{ route('treasurer.receipts.show', $record) }}" class="text-link">Open receipt</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
