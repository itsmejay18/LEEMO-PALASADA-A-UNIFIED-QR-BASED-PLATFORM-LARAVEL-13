@extends('layouts.guest')

@section('title', 'Login | LEEMO-PALASADA')
@section('hide_navigation', true)

@section('content')
    <div class="row justify-content-center align-items-center min-vh-70">
        <div class="col-lg-5">
            <div class="auth-card">
                <p class="section-label">Account Access</p>
                <h1 class="auth-title">Sign in to your market workspace</h1>
                <p class="text-muted mb-4">Use your email and password to access the right dashboard for your role.</p>

                <form method="POST" action="{{ route('login') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-link">Forgot password?</a>
                        @endif
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Login</button>
                    </div>
                </form>

                <hr class="my-4">

                <p class="mb-0 text-muted">New customer? <a class="text-link" href="{{ route('register') }}">Create an account</a></p>
            </div>
        </div>
    </div>
@endsection
