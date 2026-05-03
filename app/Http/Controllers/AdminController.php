<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Requests\UpdateUserRequest;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function users(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        return view('admin.users', [
            'search' => $search,
            'users' => User::withTrashed()
                ->with('vendor', 'roles')
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($query) use ($search): void {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%')
                            ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'like', '%'.$search.'%'))
                            ->orWhereHas('vendor', fn ($vendor) => $vendor->where('vendor_name', 'like', '%'.$search.'%'));
                    });
                })
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
            'vendors' => Vendor::orderBy('vendor_name')->get(),
            'roles' => ['Admin', 'Manager', 'Collector', 'Treasurer', 'Vendor', 'Customer'],
        ]);
    }

    public function storeUser(StoreUserRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'vendor_id' => $validated['role'] === 'Vendor' ? $validated['vendor_id'] : null,
        ]);

        if ($request->hasFile('profile_photo')) {
            $user->update([
                'profile_photo_path' => $request->file('profile_photo')->store('users/profile-photos', 'public'),
            ]);
        }

        $user->syncRoles([$validated['role']]);

        $activityLogger->log('admin.user.create', [
            'target_user' => $user->email,
            'role' => $validated['role'],
        ], $request->user(), $request->ip());

        return redirect()->route('admin.users')->with('success', 'User account created successfully.');
    }

    public function updateUser(UpdateUserRequest $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validated();

        if ($user->hasRole('Admin') && $validated['role'] !== 'Admin' && User::role('Admin')->count() <= 1) {
            return back()->with('error', 'At least one admin account must remain in the system.');
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'vendor_id' => $validated['role'] === 'Vendor' ? $validated['vendor_id'] : null,
        ];

        if (filled($validated['password'] ?? null)) {
            $payload['password'] = $validated['password'];
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $payload['profile_photo_path'] = $request->file('profile_photo')->store('users/profile-photos', 'public');
        }

        $user->update($payload);
        $user->syncRoles([$validated['role']]);

        $activityLogger->log('admin.user.update', [
            'target_user' => $user->email,
            'role' => $validated['role'],
            'vendor_id' => $validated['vendor_id'] ?? null,
        ], $request->user(), $request->ip());

        return redirect()->route('admin.users')->with('success', 'User account updated successfully.');
    }

    public function destroyUser(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot deactivate your own active session account.');
        }

        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1) {
            return back()->with('error', 'At least one admin account must remain in the system.');
        }

        $user->delete();

        $activityLogger->log('admin.user.deactivate', [
            'target_user' => $user->email,
        ], $request->user(), $request->ip());

        return back()->with('success', 'User account deactivated successfully.');
    }

    public function restoreUser(Request $request, int $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $target = User::withTrashed()->findOrFail($user);
        $target->restore();

        $activityLogger->log('admin.user.restore', [
            'target_user' => $target->email,
        ], $request->user(), $request->ip());

        return back()->with('success', 'User account restored successfully.');
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
