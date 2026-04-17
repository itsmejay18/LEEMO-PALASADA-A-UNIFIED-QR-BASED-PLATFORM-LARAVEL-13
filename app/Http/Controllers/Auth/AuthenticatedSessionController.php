<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $activityLogger->log('auth.login', [
            'email' => $request->user()?->email,
        ], $request->user(), $request->ip());

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $activityLogger->log('auth.logout', [
            'email' => $request->user()?->email,
        ], $request->user(), $request->ip());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
