@php
    $editing = isset($collection);
@endphp

<form method="POST" action="{{ $editing ? route('collector.collections.update', $collection) : route('collector.collections.store') }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label class="form-label">Vendor</label>
        <select name="vendor_id" class="form-select" required>
            <option value="">Select vendor</option>
            @foreach ($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $collection->vendor_id ?? '') == $vendor->id)>{{ $vendor->vendor_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Amount Collected</label>
        <input type="number" step="0.01" min="0.01" name="amount_collected" class="form-control" value="{{ old('amount_collected', $collection->amount_collected ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Collection Date</label>
        <input type="datetime-local" name="collection_date" class="form-control" value="{{ old('collection_date', isset($collection) ? \Illuminate\Support\Carbon::parse($collection->collection_date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Proof of Collection</label>
        <input type="file" name="proof_of_collection" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach (['pending', 'submitted', 'rejected'] as $status)
                <option value="{{ $status }}" @selected(old('status', $collection->status ?? 'submitted') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" rows="4" class="form-control">{{ old('remarks', $collection->remarks ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <button class="btn btn-brand" type="submit">{{ $editing ? 'Update Collection' : 'Save Collection' }}</button>
    </div>
</form>
