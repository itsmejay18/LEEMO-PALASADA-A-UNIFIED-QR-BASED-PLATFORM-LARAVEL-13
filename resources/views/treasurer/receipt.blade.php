@extends('layouts.app')

@section('title', 'Receipt '.$record->official_receipt_number.' | LEEMO-PALASADA')

@section('content')
    <div class="content-card p-5 receipt-sheet">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <p class="section-label">Official Receipt</p>
                <h1 class="page-title mb-1">{{ $record->official_receipt_number }}</h1>
                <p class="text-muted mb-0">Verification recorded on {{ \Illuminate\Support\Carbon::parse($record->verification_date)->format('M d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('treasurer.collections.index') }}" class="btn btn-outline-brand">Back to Queue</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="list-card h-100">
                    <h2 class="subheading mb-3">Vendor Details</h2>
                    <p class="mb-1 fw-semibold">{{ $record->collection->vendor->vendor_name }}</p>
                    <p class="mb-1 text-muted">Stall {{ $record->collection->vendor->stall_number }}</p>
                    <p class="mb-0 text-muted">{{ $record->collection->vendor->contact_number }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="list-card h-100">
                    <h2 class="subheading mb-3">Verification Details</h2>
                    <p class="mb-1">Collector: {{ $record->collection->collector->name }}</p>
                    <p class="mb-1">Treasurer: {{ $record->treasurer->name }}</p>
                    <p class="mb-1">Verified Amount: PHP {{ number_format($record->amount_verified, 2) }}</p>
                    <p class="mb-0">Status: {{ ucfirst($record->collection->status) }}</p>
                </div>
            </div>
        </div>

        @if ($record->notes)
            <div class="list-card mt-4">
                <h2 class="subheading mb-2">Treasurer Notes</h2>
                <p class="mb-0 text-muted">{{ $record->notes }}</p>
            </div>
        @endif
    </div>
@endsection
