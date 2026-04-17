@extends('layouts.app')

@section('title', 'System Settings | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card p-4">
                <p class="section-label">Platform Settings</p>
                <h1 class="page-title mb-3">System-wide configuration</h1>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label class="form-label">Market Name</label>
                        <input type="text" name="market_name" class="form-control" value="{{ old('market_name', $settings['market_name'] ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Support Email</label>
                        <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings['support_email'] ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Announcement</label>
                        <textarea name="announcement" rows="4" class="form-control">{{ old('announcement', $settings['announcement'] ?? '') }}</textarea>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label">Featured Zone</label>
                        <input type="text" name="featured_zone" class="form-control" value="{{ old('featured_zone', $settings['featured_zone'] ?? '') }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">QRPh Simulation</label>
                        <select name="enable_qrph" class="form-select">
                            <option value="1" @selected(old('enable_qrph', $settings['enable_qrph'] ?? '1') == '1')>Enabled</option>
                            <option value="0" @selected(old('enable_qrph', $settings['enable_qrph'] ?? '1') == '0')>Disabled</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand" type="submit">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card p-4">
                <h2 class="subheading mb-3">Audit log</h2>
                <div class="table-responsive">
                    <table class="table table-theme align-middle">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="small text-muted">{{ $log->details }}</td>
                                    <td>{{ optional($log->created_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
