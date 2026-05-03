@php
    $editing = isset($vendor);
    $formId = $editing ? 'vendor_is_active_'.$vendor->id : 'vendor_is_active_new';
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

    <div class="col-md-4">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $vendor->category ?? '') }}" placeholder="Produce, Meat, Food">
    </div>

    <div class="col-md-4">
        <label class="form-label">Contract Start</label>
        <input type="date" name="contract_start_date" class="form-control" value="{{ old('contract_start_date', isset($vendor) && $vendor->contract_start_date ? $vendor->contract_start_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Contract End</label>
        <input type="date" name="contract_end_date" class="form-control" value="{{ old('contract_end_date', isset($vendor) && $vendor->contract_end_date ? $vendor->contract_end_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Monthly Rent</label>
        <input type="number" step="0.01" min="0" name="monthly_rent" class="form-control" value="{{ old('monthly_rent', $vendor->monthly_rent ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="{{ $formId }}" @checked(old('is_active', $vendor->is_active ?? true))>
            <label class="form-check-label" for="{{ $formId }}">Vendor is active</label>
        </div>
    </div>

    <div class="col-12">
        <button class="btn btn-brand" type="submit">{{ $editing ? 'Update Vendor' : 'Create Vendor' }}</button>
    </div>
</form>
