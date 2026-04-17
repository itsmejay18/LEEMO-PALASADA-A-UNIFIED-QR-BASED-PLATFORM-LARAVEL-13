<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterCustomerRequest;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(RegisterCustomerRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $user = $request->persist();
        Role::findOrCreate('Customer', 'web');
        $user->assignRole('Customer');

        event(new Registered($user));

        Auth::login($user);

        $activityLogger->log('auth.register', [
            'email' => $user->email,
            'role' => 'Customer',
        ], $user, $request->ip());

        return redirect(route('dashboard', absolute: false));
    }
}
