@extends('layouts.app')

@section('title', 'Verification Queue | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Collection Verification</p>
        <h1 class="page-title mb-1">Review collection submissions</h1>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Collector</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $collection)
                        <tr>
                            <td>{{ $collection->vendor->vendor_name }}</td>
                            <td>{{ $collection->collector->name }}</td>
                            <td><span class="badge-soft">{{ ucfirst($collection->status) }}</span></td>
                            <td>PHP {{ number_format($collection->amount_collected, 2) }}</td>
                            <td>
                                @if ($collection->treasurerRecord)
                                    <a href="{{ route('treasurer.receipts.show', $collection->treasurerRecord) }}" class="btn btn-sm btn-outline-brand">Receipt</a>
                                @elseif ($collection->status === 'submitted')
                                    <button type="button" class="btn btn-sm btn-brand" data-coreui-toggle="modal" data-coreui-target="#verifyCollectionModal{{ $collection->id }}">Verify</button>
                                @else
                                    <span class="text-muted small">Awaiting resubmission</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $collections->links() }}
    </div>

    @foreach ($collections as $collection)
        @if (!$collection->treasurerRecord && $collection->status === 'submitted')
            @php
                $suggestedReceiptNumber = 'OR-'.now()->format('Y').'-'.str_pad((string) ($collection->id + 1000), 5, '0', STR_PAD_LEFT);
            @endphp
            <div class="modal fade" id="verifyCollectionModal{{ $collection->id }}" tabindex="-1" aria-labelledby="verifyCollectionModal{{ $collection->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="verifyCollectionModal{{ $collection->id }}Label">Verify Collection #{{ $collection->id }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="list-card mb-3">
                                <div class="fw-semibold">{{ $collection->vendor->vendor_name }}</div>
                                <small class="text-muted">Collector: {{ $collection->collector->name }} | PHP {{ number_format($collection->amount_collected, 2) }}</small>
                            </div>

                            <form method="POST" action="{{ route('treasurer.records.store') }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="collection_id" value="{{ $collection->id }}">

                                <div class="col-md-6">
                                    <label class="form-label">Verified Amount</label>
                                    <input type="number" step="0.01" min="0" name="amount_verified" class="form-control" value="{{ old('amount_verified', $collection->amount_collected) }}" required>
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
            </div>
        @endif
    @endforeach
@endsection
