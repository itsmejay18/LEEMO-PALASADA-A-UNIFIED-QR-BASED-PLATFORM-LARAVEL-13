@extends('layouts.app')

@section('title', 'Checkout | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-card p-4 h-100">
                <p class="section-label">Order Summary</p>
                <h1 class="page-title mb-3">Confirm your purchase</h1>
                <div class="vstack gap-3">
                    @foreach ($cartItems as $item)
                        <div class="list-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $item['product']->product_name }}</div>
                                <small class="text-muted">{{ $item['product']->vendor->vendor_name }} | Qty {{ $item['quantity'] }}</small>
                            </div>
                            <strong>PHP {{ number_format($item['subtotal'], 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-card p-4">
                <p class="section-label">Payment Simulation</p>
                <h2 class="subheading mb-3">Select your payment option</h2>

                <form method="POST" action="{{ route('customer.checkout.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="qrph">QRPh</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="checkout-total">PHP {{ number_format($cartTotal, 2) }}</div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Complete Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
