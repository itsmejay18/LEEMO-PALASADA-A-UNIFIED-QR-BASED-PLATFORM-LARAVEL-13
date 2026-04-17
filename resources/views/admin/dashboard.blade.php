@extends('layouts.app')

@section('title', 'Admin Dashboard | LEEMO-PALASADA')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="section-label">Administrator Control Center</p>
            <h1 class="page-title mb-1">System overview and security monitoring</h1>
            <p class="text-muted mb-0">Manage roles, monitor activity, and keep the platform healthy.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-brand">Manage Users</a>
            <a href="{{ route('admin.settings') }}" class="btn btn-outline-brand">System Settings</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="stat-card"><span>Total Users</span><strong>{{ $stats['users'] }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Total Vendors</span><strong>{{ number_format($stats['vendors']) }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Monthly Sales</span><strong>PHP {{ number_format($stats['monthly_sales'], 2) }}</strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Pending Collections</span><strong>{{ $stats['pending_collections'] }}</strong></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Recent users</h2>
                <div class="table-responsive">
                    <table class="table table-theme align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td><span class="badge-soft">{{ $user->primaryRole() ?? 'Customer' }}</span></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card p-4 h-100">
                <h2 class="subheading mb-3">Activity feed</h2>
                <div class="vstack gap-3">
                    @foreach ($recentLogs as $log)
                        <div class="list-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $log->action }}</div>
                                    <div class="small text-muted">{{ $log->user?->name ?? 'System' }}</div>
                                </div>
                                <small class="text-muted">{{ optional($log->created_at)->diffForHumans() }}</small>
                            </div>
                            @if ($log->details)
                                <p class="small text-muted mb-0 mt-2">{{ $log->details }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
