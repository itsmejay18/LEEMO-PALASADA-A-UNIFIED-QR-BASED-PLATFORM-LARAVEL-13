@extends('layouts.auth-coreui')

@section('title', 'Login | LEEMO-PALASADA')

@section('content')
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-group d-block d-md-flex row">
                        <div class="card col-md-7 p-4 mb-0">
                            <div class="card-body">
                                <h1>Login</h1>
                                <p class="text-body-secondary">Sign in to your market workspace</p>

                                @include('partials.alerts')

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <i class="icon cil-user"></i>
                                        </span>
                                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus autocomplete="username">
                                    </div>

                                    <div class="input-group mb-4">
                                        <span class="input-group-text">
                                            <i class="icon cil-lock-locked"></i>
                                        </span>
                                        <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="current-password">
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <button class="btn btn-brand px-4" type="submit">Login</button>
                                        </div>
                                        <div class="col-6 text-end">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="btn btn-link px-0">Forgot password?</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                        <label class="form-check-label" for="remember_me">Remember me</label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card col-md-5 text-white bg-primary py-5">
                            <div class="card-body text-center d-flex align-items-center">
                                <div>
                                    <h2>Create Account</h2>
                                    <p>Register as a customer to scan product QR codes, save vendors, and track purchases.</p>
                                    <a class="btn btn-lg btn-outline-light mt-3" href="{{ route('register') }}">Register Now</a>
                                    <div class="mt-4">
                                        <a class="text-white" href="{{ route('landing') }}">Back to landing</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
