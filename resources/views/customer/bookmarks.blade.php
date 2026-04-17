@extends('layouts.app')

@section('title', 'Bookmarks | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Favorite Vendors</p>
        <h1 class="page-title mb-1">Your bookmarked stalls</h1>
    </div>

    <div class="row g-4">
        @foreach ($vendors as $vendor)
            <div class="col-md-6 col-xl-4">
                <div class="vendor-card h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1">{{ $vendor->vendor_name }}</h2>
                            <p class="text-muted mb-0">Stall {{ $vendor->stall_number }}</p>
                        </div>
                        <span class="badge-soft">{{ $vendor->products->count() }} products</span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-outline-brand btn-sm">Open Profile</a>
                        @if ($vendor->marketMap)
                            <a href="{{ route('qr.locations.show', $vendor->marketMap->qr_location_code) }}" class="btn btn-outline-brand btn-sm">Navigate</a>
                        @endif
                        <form method="POST" action="{{ route('customer.bookmarks.toggle') }}">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            <button class="btn btn-outline-danger btn-sm" type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $vendors->links() }}
    </div>
@endsection
