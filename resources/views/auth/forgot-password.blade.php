@extends('layouts.guest')

@section('title', 'Forgot Password | LEEMO-PALASADA')

@section('content')
    <div class="row justify-content-center min-vh-70">
        <div class="col-lg-5">
            <div class="auth-card">
                <p class="section-label">Password Recovery</p>
                <h1 class="auth-title">Reset your password</h1>
                <p class="text-muted mb-4">Enter your email address and we will send a secure password reset link.</p>

                <form method="POST" action="{{ route('password.email') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Email Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
