@extends('layouts.app')

@section('title', 'User Management | LEEMO-PALASADA')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="section-label">User Management</p>
            <h1 class="page-title mb-1">Assign roles and vendor links</h1>
        </div>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Vendor Link</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="{{ $user->trashed() ? 'table-secondary' : '' }}">
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if ($user->trashed())
                                    <small class="text-danger">Archived account</small>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge-soft">{{ $user->primaryRole() ?? 'Customer' }}</span></td>
                            <td>{{ $user->vendor?->vendor_name ?? 'Not linked' }}</td>
                            <td style="min-width: 320px;">
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="row g-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-5">
                                        <select name="role" class="form-select">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}" @selected(($user->primaryRole() ?? 'Customer') === $role)>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <select name="vendor_id" class="form-select">
                                            <option value="">No Vendor Link</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" @selected($user->vendor_id === $vendor->id)>{{ $vendor->vendor_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-brand w-100" type="submit">Save</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
