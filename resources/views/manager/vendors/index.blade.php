@extends('layouts.app')

@section('title', 'Vendor Management | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Vendor Management</p>
            <h1 class="page-title mb-1">Manage stalls and vendor records</h1>
        </div>
        <a href="{{ route('manager.vendors.create') }}" class="btn btn-brand">Add Vendor</a>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Stall</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr class="{{ $vendor->trashed() ? 'table-secondary' : '' }}">
                            <td>
                                <div class="fw-semibold">{{ $vendor->vendor_name }}</div>
                                <small class="text-muted">{{ $vendor->email }}</small>
                            </td>
                            <td>{{ $vendor->stall_number }}</td>
                            <td>{{ $vendor->contact_number }}</td>
                            <td><span class="badge-soft">{{ $vendor->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('manager.vendors.edit', $vendor) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                                    <form method="POST" action="{{ route('manager.vendors.destroy', $vendor) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Archive</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $vendors->links() }}
    </div>
@endsection
