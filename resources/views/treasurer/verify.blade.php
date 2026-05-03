@extends('layouts.app')

@section('title', 'Verify Collection | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4 h-100">
                <p class="section-label">Collection Summary</p>
                <h1 class="page-title fs-3 mb-3">{{ $collection->vendor->vendor_name }}</h1>
                <ul class="list-unstyled text-muted mb-0">
                    <li class="mb-2">Collector: {{ $collection->collector->name }}</li>
                    <li class="mb-2">Amount: PHP {{ number_format($collection->amount_collected, 2) }}</li>
                    <li class="mb-2">Date: {{ \Illuminate\Support\Carbon::parse($collection->collection_date)->format('M d, Y h:i A') }}</li>
                    <li>Status: {{ ucfirst($collection->status) }}</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-card p-4">
                <p class="section-label">Treasurer Action</p>
                <h2 class="subheading mb-3">Record verification and generate receipt</h2>

                <form method="POST" action="{{ route('treasurer.records.store') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="collection_id" value="{{ $collection->id }}">

                    <div class="col-md-6">
                        <label class="form-label">Verified Amount</label>
                        <input type="number" step="0.01" min="0.01" name="amount_verified" class="form-control" value="{{ old('amount_verified', $collection->amount_collected) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Verification Date</label>
                        <input type="datetime-local" name="verification_date" class="form-control" value="{{ old('verification_date', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Official Receipt Number</label>
                        <input type="text" name="official_receipt_number" class="form-control" value="{{ old('official_receipt_number', $suggestedReceiptNumber) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Collection Status</label>
                        <select name="collection_status" class="form-select">
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand" type="submit">Save Verification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
