@extends('layouts.guest')

@section('title', $vendor->vendor_name.' | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4 h-100">
                <p class="section-label">Vendor Profile</p>
                <h1 class="page-title mb-2">{{ $vendor->vendor_name }}</h1>
                <p class="text-muted">Stall {{ $vendor->stall_number }} | {{ $vendor->contact_number }}</p>
                <p class="mb-3">{{ $vendor->email }}</p>
                @auth
                    @if(auth()->user()->hasRole('Customer'))
                        <form method="POST" action="{{ route('customer.bookmarks.toggle') }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            <button class="btn btn-outline-brand" type="submit">
                                {{ auth()->user()->bookmarkedVendors()->whereKey($vendor->id)->exists() ? 'Remove Bookmark' : 'Bookmark Vendor' }}
                            </button>
                        </form>
                    @endif
                @endauth
                @if ($vendor->marketMap)
                    <a href="{{ route('qr.locations.show', $vendor->marketMap->qr_location_code) }}" class="btn btn-outline-brand">Open Stall Navigation</a>
                @endif
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-card p-4">
                <h2 class="subheading mb-3">Available products</h2>
                <div class="row g-3">
                    @foreach ($vendor->products as $product)
                        <div class="col-md-6">
                            <div class="product-card h-100">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <h3 class="h6 mb-0">{{ $product->product_name }}</h3>
                                    <span class="badge-soft">PHP {{ number_format($product->price, 2) }}</span>
                                </div>
                                <p class="small text-muted">{{ $product->description }}</p>
                                <a href="{{ route('qr.products.show', $product) }}" class="text-link">View QR product page</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
