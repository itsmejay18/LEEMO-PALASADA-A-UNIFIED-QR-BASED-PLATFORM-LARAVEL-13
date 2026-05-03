@extends('layouts.app')

@section('title', 'Products | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Product Management</p>
            <h1 class="page-title mb-1">Manage your product catalog</h1>
        </div>
        <button type="button" class="btn btn-brand" data-coreui-toggle="modal" data-coreui-target="#productCreateModal">Add Product</button>
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
                                        <button type="button" class="btn btn-sm btn-outline-brand" data-coreui-toggle="modal" data-coreui-target="#productEditModal{{ $product->id }}">Edit</button>
                                        <form method="POST" action="{{ route('vendor.products.availability', $product) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-brand" type="submit">Toggle</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-coreui-toggle="modal" data-coreui-target="#productArchiveModal{{ $product->id }}">Archive</button>
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

    <div class="modal fade" id="productCreateModal" tabindex="-1" aria-labelledby="productCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="productCreateModalLabel">Add Product</h2>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('vendor.products._form')
                </div>
            </div>
        </div>
    </div>

    @foreach ($products as $product)
        @if (!$product->trashed())
            <div class="modal fade" id="productEditModal{{ $product->id }}" tabindex="-1" aria-labelledby="productEditModal{{ $product->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="productEditModal{{ $product->id }}Label">Edit {{ $product->product_name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('vendor.products._form', ['product' => $product])
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="productArchiveModal{{ $product->id }}" tabindex="-1" aria-labelledby="productArchiveModal{{ $product->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="productArchiveModal{{ $product->id }}Label">Archive {{ $product->product_name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">This product will be archived and removed from active customer browsing.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-brand" data-coreui-dismiss="modal">Cancel</button>
                            <form method="POST" action="{{ route('vendor.products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" type="submit">Archive Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
