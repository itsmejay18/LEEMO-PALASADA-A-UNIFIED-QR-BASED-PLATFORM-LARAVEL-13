@extends('layouts.guest')

@section('title', 'Reset Password | LEEMO-PALASADA')

@section('content')
    <div class="row justify-content-center min-vh-70">
        <div class="col-lg-5">
            <div class="auth-card">
                <p class="section-label">Password Recovery</p>
                <h1 class="auth-title">Choose a new password</h1>

                <form method="POST" action="{{ route('password.store') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="col-12">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="password">New Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
