@extends('layouts.app')

@section('title', 'Vendor Management | LEEMO-PALASADA')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="section-label">Vendor Management</p>
            <h1 class="page-title mb-1">Manage stalls and vendor records</h1>
        </div>
        <button type="button" class="btn btn-brand" data-coreui-toggle="modal" data-coreui-target="#vendorCreateModal">Add Vendor</button>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Stall</th>
                        <th>Category</th>
                        <th>Contract</th>
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
                            <td>{{ $vendor->category ?? 'Uncategorized' }}</td>
                            <td>
                                @if ($vendor->contract_end_date)
                                    <div class="fw-semibold">{{ $vendor->contract_end_date->format('M d, Y') }}</div>
                                    <small class="text-muted">PHP {{ number_format($vendor->monthly_rent ?? 0, 2) }}/mo</small>
                                @else
                                    <span class="text-muted small">No contract date</span>
                                @endif
                            </td>
                            <td>{{ $vendor->contact_number }}</td>
                            <td><span class="badge-soft">{{ $vendor->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!$vendor->trashed())
                                        <button type="button" class="btn btn-sm btn-outline-brand" data-coreui-toggle="modal" data-coreui-target="#vendorEditModal{{ $vendor->id }}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-coreui-toggle="modal" data-coreui-target="#vendorArchiveModal{{ $vendor->id }}">Archive</button>
                                    @else
                                        <span class="text-muted small">Archived</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $vendors->links() }}
    </div>

    <div class="modal fade" id="vendorCreateModal" tabindex="-1" aria-labelledby="vendorCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="vendorCreateModalLabel">Add Vendor</h2>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('manager.vendors._form')
                </div>
            </div>
        </div>
    </div>

    @foreach ($vendors as $vendor)
        @if (!$vendor->trashed())
            <div class="modal fade" id="vendorEditModal{{ $vendor->id }}" tabindex="-1" aria-labelledby="vendorEditModal{{ $vendor->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="vendorEditModal{{ $vendor->id }}Label">Edit {{ $vendor->vendor_name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('manager.vendors._form', ['vendor' => $vendor])
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="vendorArchiveModal{{ $vendor->id }}" tabindex="-1" aria-labelledby="vendorArchiveModal{{ $vendor->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="vendorArchiveModal{{ $vendor->id }}Label">Archive {{ $vendor->vendor_name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">This vendor record will be archived and hidden from active workflows.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-brand" data-coreui-dismiss="modal">Cancel</button>
                            <form method="POST" action="{{ route('manager.vendors.destroy', $vendor) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" type="submit">Archive Vendor</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
