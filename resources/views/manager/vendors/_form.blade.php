@php
    $editing = isset($vendor);
@endphp

<form method="POST" action="{{ $editing ? route('manager.vendors.update', $vendor) : route('manager.vendors.store') }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label class="form-label">Vendor Name</label>
        <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name', $vendor->vendor_name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Stall Number</label>
        <input type="text" name="stall_number" class="form-control" value="{{ old('stall_number', $vendor->stall_number ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $vendor->contact_number ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $vendor->email ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $vendor->is_active ?? true))>
            <label class="form-check-label" for="is_active">Vendor is active</label>
        </div>
    </div>

    <div class="col-12">
        <button class="btn btn-brand" type="submit">{{ $editing ? 'Update Vendor' : 'Create Vendor' }}</button>
    </div>
</form>
