@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')

@section('title', $product->product_name.' | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4 h-100 text-center">
                <p class="section-label">Product QR</p>
                <img src="{{ route('qr.products.image', $product) }}" alt="Product QR code" class="img-fluid qr-preview mb-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('qr.products.download', $product) }}" class="btn btn-outline-brand">Download QR Code</a>
                    <a href="{{ route('vendors.show', $product->vendor) }}" class="btn btn-outline-brand">View Vendor Profile</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-card p-4 h-100">
                <p class="section-label">Scanned Product</p>
                <h1 class="page-title mb-1">{{ $product->product_name }}</h1>
                <p class="text-muted mb-3">{{ $product->vendor->vendor_name }} | Stall {{ $product->vendor->stall_number }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-4"><div class="stat-card compact"><span>Price</span><strong>PHP {{ number_format($product->price, 2) }}</strong></div></div>
                    <div class="col-md-4"><div class="stat-card compact"><span>Stock</span><strong>{{ $product->stock_quantity }}</strong></div></div>
                    <div class="col-md-4"><div class="stat-card compact"><span>Status</span><strong>{{ $product->is_available ? 'Available' : 'Unavailable' }}</strong></div></div>
                </div>

                <p class="text-muted">{{ $product->description }}</p>

                @auth
                    @if(auth()->user()->hasRole('Customer'))
                        <form method="POST" action="{{ route('customer.cart.add', $product) }}" class="row g-3 align-items-end mt-3">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" min="1" max="{{ $product->stock_quantity }}" class="form-control" value="{{ max(1, $cartQuantity) }}" required>
                            </div>
                            <div class="col-md-8">
                                <button class="btn btn-brand" type="submit" @disabled(!$product->is_available)>Add to Cart</button>
                                <a href="{{ route('customer.cart') }}" class="btn btn-outline-brand">Open Cart</a>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="list-card mt-4">
                        <strong>Customer login required for checkout</strong>
                        <p class="small text-muted mb-2">You can browse this product now, then sign in to add it to your cart.</p>
                        <a href="{{ route('login') }}" class="btn btn-brand">Login to Purchase</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
