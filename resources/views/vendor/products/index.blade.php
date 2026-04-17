@extends('layouts.app')

@section('title', 'Products | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Product Management</p>
            <h1 class="page-title mb-1">Manage your product catalog</h1>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-brand">Add Product</a>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Availability</th>
                        <th>QR</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="{{ $product->trashed() ? 'table-secondary' : '' }}">
                            <td>
                                <div class="fw-semibold">{{ $product->product_name }}</div>
                                <small class="text-muted">{{ $product->description }}</small>
                            </td>
                            <td>PHP {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td><span class="badge-soft">{{ $product->is_available ? 'Available' : 'Unavailable' }}</span></td>
                            <td>
                                @if (!$product->trashed())
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-outline-brand" href="{{ route('qr.products.show', $product) }}">View</a>
                                        <a class="btn btn-sm btn-outline-brand" href="{{ route('qr.products.download', $product) }}">Download</a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if (!$product->trashed())
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                                        <form method="POST" action="{{ route('vendor.products.availability', $product) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-brand" type="submit">Toggle</button>
                                        </form>
                                        <form method="POST" action="{{ route('vendor.products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Archive</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Archived</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
@endsection
