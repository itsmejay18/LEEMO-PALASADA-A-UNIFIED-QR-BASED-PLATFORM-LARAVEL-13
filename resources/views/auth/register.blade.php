@extends('layouts.auth-coreui')

@section('title', 'Register | LEEMO-PALASADA')

@section('content')
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4 mx-4">
                        <div class="card-body p-4">
                            <h1>Register</h1>
                            <p class="text-body-secondary">Create your customer account</p>

                            @include('partials.alerts')

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="icon cil-user"></i>
                                    </span>
                                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus autocomplete="name">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="icon cil-envelope-open"></i>
                                    </span>
                                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="username">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="icon cil-lock-locked"></i>
                                    </span>
                                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                                </div>

                                <div class="input-group mb-4">
                                    <span class="input-group-text">
                                        <i class="icon cil-lock-locked"></i>
                                    </span>
                                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                                </div>

                                <button class="btn btn-brand w-100" type="submit">Create Account</button>
                            </form>

                            <div class="d-flex justify-content-between gap-3 mt-4">
                                <a class="btn btn-link px-0" href="{{ route('login') }}">Sign in instead</a>
                                <a class="btn btn-link px-0" href="{{ route('landing') }}">Back to landing</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
