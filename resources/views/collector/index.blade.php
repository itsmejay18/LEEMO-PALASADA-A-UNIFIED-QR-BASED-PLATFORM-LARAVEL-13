@extends('layouts.app')

@section('title', 'Collections | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Collection Records</p>
            <h1 class="page-title mb-1">Submitted vendor remittances</h1>
        </div>
        <button type="button" class="btn btn-brand" data-coreui-toggle="modal" data-coreui-target="#collectionCreateModal">New Collection</button>
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
                                @if ($collection->status !== 'verified')
                                    <button type="button" class="btn btn-sm btn-outline-brand" data-coreui-toggle="modal" data-coreui-target="#collectionEditModal{{ $collection->id }}">Edit</button>
                                @else
                                    <span class="text-muted small">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $collections->links() }}
    </div>

    <div class="modal fade" id="collectionCreateModal" tabindex="-1" aria-labelledby="collectionCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="collectionCreateModalLabel">Record Collection</h2>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('collector._form')
                </div>
            </div>
        </div>
    </div>

    @foreach ($collections as $collection)
        @if ($collection->status !== 'verified')
            <div class="modal fade" id="collectionEditModal{{ $collection->id }}" tabindex="-1" aria-labelledby="collectionEditModal{{ $collection->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="collectionEditModal{{ $collection->id }}Label">Edit Collection #{{ $collection->id }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('collector._form', ['collection' => $collection])
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
