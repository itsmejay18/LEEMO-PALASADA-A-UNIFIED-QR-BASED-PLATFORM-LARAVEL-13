@extends('layouts.app')

@section('title', 'Collections | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Collection Records</p>
            <h1 class="page-title mb-1">Submitted vendor remittances</h1>
        </div>
        <a href="{{ route('collector.collections.create') }}" class="btn btn-brand">New Collection</a>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $collection)
                        <tr>
                            <td>{{ $collection->vendor->vendor_name }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($collection->collection_date)->format('M d, Y h:i A') }}</td>
                            <td><span class="badge-soft">{{ ucfirst($collection->status) }}</span></td>
                            <td>PHP {{ number_format($collection->amount_collected, 2) }}</td>
                            <td>
                                <a href="{{ route('collector.collections.edit', $collection) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $collections->links() }}
    </div>
@endsection
