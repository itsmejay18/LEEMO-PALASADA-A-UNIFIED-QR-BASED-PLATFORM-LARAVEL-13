@extends('layouts.app')

@section('title', 'User Management | LEEMO-PALASADA')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="section-label">User Management</p>
            <h1 class="page-title mb-1">Assign roles and vendor links</h1>
        </div>
        <button type="button" class="btn btn-brand" data-coreui-toggle="modal" data-coreui-target="#userCreateModal">Add User</button>
    </div>

    <div class="content-card p-4">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-9">
                <label class="form-label" for="user-search">Search Users</label>
                <input id="user-search" type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Name, email, role, or vendor">
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-outline-brand" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Vendor Link</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                            <td><span class="badge-soft">{{ $user->trashed() ? 'Deactivated' : 'Active' }}</span></td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!$user->trashed())
                                        <button type="button" class="btn btn-sm btn-outline-brand" data-coreui-toggle="modal" data-coreui-target="#userEditModal{{ $user->id }}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-coreui-toggle="modal" data-coreui-target="#userDeactivateModal{{ $user->id }}">Deactivate</button>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-brand" type="submit">Restore</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>

    <div class="modal fade" id="userCreateModal" tabindex="-1" aria-labelledby="userCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="userCreateModalLabel">Add User</h2>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin._user_form')
                </div>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
        @if (!$user->trashed())
            <div class="modal fade" id="userEditModal{{ $user->id }}" tabindex="-1" aria-labelledby="userEditModal{{ $user->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="userEditModal{{ $user->id }}Label">Edit {{ $user->name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('admin._user_form', ['managedUser' => $user])
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!$user->trashed())
            <div class="modal fade" id="userDeactivateModal{{ $user->id }}" tabindex="-1" aria-labelledby="userDeactivateModal{{ $user->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="userDeactivateModal{{ $user->id }}Label">Deactivate {{ $user->name }}</h2>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">This account will no longer be able to sign in until restored.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-brand" data-coreui-dismiss="modal">Cancel</button>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" type="submit">Deactivate User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
