@extends('layouts.app')

@section('title', 'Shopping Cart | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Shopping Cart</p>
            <h1 class="page-title mb-1">Review your selected items</h1>
        </div>
        <a href="{{ route('map.index') }}" class="btn btn-outline-brand">Continue Shopping</a>
    </div>

    <div class="content-card p-4">
        @if ($cartItems->isEmpty())
            <p class="text-muted mb-0">Your cart is empty. Scan a product QR or browse vendor listings to begin.</p>
        @else
            <div class="table-responsive">
                <table class="table table-theme align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td>{{ $item['product']->product_name }}</td>
                                <td>{{ $item['product']->vendor->vendor_name }}</td>
                                <td>
                                    <form method="POST" action="{{ route('customer.cart.update', $item['product']) }}" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" min="1" max="{{ $item['product']->stock_quantity }}" name="quantity" class="form-control form-control-sm w-auto" value="{{ $item['quantity'] }}">
                                        <button class="btn btn-sm btn-outline-brand" type="submit">Update</button>
                                    </form>
                                </td>
                                <td>PHP {{ number_format($item['subtotal'], 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('customer.cart.remove', $item['product']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                <div class="fw-semibold">Cart Total: PHP {{ number_format($cartTotal, 2) }}</div>
                <a href="{{ route('customer.checkout') }}" class="btn btn-brand">Proceed to Checkout</a>
            </div>
        @endif
    </div>
@endsection
