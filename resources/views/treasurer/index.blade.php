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
                                    <a href="{{ route('treasurer.collections.verify', $collection) }}" class="btn btn-sm btn-brand">Verify</a>
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
@endsection
