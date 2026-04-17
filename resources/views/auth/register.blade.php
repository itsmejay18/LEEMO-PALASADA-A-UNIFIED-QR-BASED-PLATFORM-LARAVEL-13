@extends('layouts.guest')

@section('title', 'Register | LEEMO-PALASADA')

@section('content')
    <div class="row justify-content-center min-vh-70">
        <div class="col-lg-6">
            <div class="auth-card">
                <p class="section-label">Customer Registration</p>
                <h1 class="auth-title">Create your customer account</h1>
                <p class="text-muted mb-4">Registration creates a Customer account only. Role changes are handled by the admin panel.</p>

                <form method="POST" action="{{ route('register') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label" for="name">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus autocomplete="name">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="username">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Create Account</button>
                    </div>
                </form>

                <hr class="my-4">
                <p class="mb-0 text-muted">Already registered? <a class="text-link" href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
@endsection
