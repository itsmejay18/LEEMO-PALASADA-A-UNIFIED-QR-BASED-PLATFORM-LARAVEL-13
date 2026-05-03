@php
    $editing = isset($managedUser);
    $formUser = $managedUser ?? null;
    $currentRole = old('role', $formUser?->primaryRole() ?? 'Customer');
    $photoUrl = $formUser?->profile_photo_path
        ? asset('storage/'.$formUser->profile_photo_path)
        : Vite::asset('resources/vendor/coreui-kit/assets/img/avatars/8.jpg');
@endphp

<form method="POST" action="{{ $editing ? route('admin.users.update', $formUser) : route('admin.users.store') }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="col-12 d-flex align-items-center gap-3">
        @if($editing)
            <img class="profile-photo-preview" src="{{ $photoUrl }}" alt="{{ $formUser->name }} profile photo">
        @endif
        <div class="flex-grow-1">
            <label class="form-label">Profile Photo</label>
            <input type="file" name="profile_photo" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $formUser->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $formUser->email ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" {{ $editing ? '' : 'required' }}>
        @if($editing)
            <small class="text-muted">Leave blank to keep the current password.</small>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected($currentRole === $role)>{{ $role }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Vendor Link</label>
        <select name="vendor_id" class="form-select">
            <option value="">No Vendor Link</option>
            @foreach ($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected((string) old('vendor_id', $formUser->vendor_id ?? '') === (string) $vendor->id)>{{ $vendor->vendor_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <button class="btn btn-brand" type="submit">{{ $editing ? 'Update User' : 'Create User' }}</button>
    </div>
</form>
