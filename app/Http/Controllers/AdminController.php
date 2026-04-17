<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\ActivityLog;
use App\Models\Collection;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'vendors' => Vendor::count(),
                'monthly_sales' => Transaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
                'pending_collections' => Collection::where('status', 'submitted')->count(),
            ],
            'recentUsers' => User::with('vendor', 'roles')->latest()->take(6)->get(),
            'recentLogs' => ActivityLog::with('user')->latest('created_at')->take(8)->get(),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::withTrashed()->with('vendor', 'roles')->orderBy('name')->paginate(15),
            'vendors' => Vendor::orderBy('vendor_name')->get(),
            'roles' => ['Admin', 'Manager', 'Collector', 'Treasurer', 'Vendor', 'Customer'],
        ]);
    }

    public function updateUserRole(UpdateUserRoleRequest $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validated();

        if ($user->hasRole('Admin') && $validated['role'] !== 'Admin' && User::role('Admin')->count() <= 1) {
            return back()->with('error', 'At least one admin account must remain in the system.');
        }

        $user->update([
            'vendor_id' => $validated['role'] === 'Vendor' ? $validated['vendor_id'] : null,
        ]);
        $user->syncRoles([$validated['role']]);

        $activityLogger->log('admin.role.update', [
            'target_user' => $user->email,
            'new_role' => $validated['role'],
            'vendor_id' => $validated['vendor_id'] ?? null,
        ], $request->user(), $request->ip());

        return back()->with('success', 'User role updated successfully.');
    }

    public function settings(): View
    {
        return view('admin.settings', [
            'settings' => Setting::query()->pluck('value', 'key'),
            'logs' => ActivityLog::with('user')->latest('created_at')->paginate(20),
        ]);
    }

    public function updateSettings(UpdateSettingRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $settings = [
            'market_name' => ['label' => 'Market Name', 'group' => 'branding'],
            'support_email' => ['label' => 'Support Email', 'group' => 'contact'],
            'announcement' => ['label' => 'Announcement', 'group' => 'general'],
            'featured_zone' => ['label' => 'Featured Zone', 'group' => 'marketing'],
            'enable_qrph' => ['label' => 'Enable QRPh Simulation', 'group' => 'payments'],
        ];

        foreach ($settings as $key => $meta) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'label' => $meta['label'],
                    'group' => $meta['group'],
                    'value' => (string) ($request->validated()[$key] ?? ''),
                ],
            );
        }

        $activityLogger->log('admin.settings.update', $request->validated(), $request->user(), $request->ip());

        return redirect()->route('admin.settings')->with('success', 'System settings saved successfully.');
    }
}
