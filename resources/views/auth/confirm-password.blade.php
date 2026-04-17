@extends('layouts.app')

@section('title', 'Confirm Password | LEEMO-PALASADA')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="content-card p-4">
                <p class="section-label">Secure Confirmation</p>
                <h1 class="page-title mb-3">Confirm your password</h1>
                <p class="text-muted">For your protection, please re-enter your password before continuing.</p>

                <form method="POST" action="{{ route('password.confirm') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-brand w-100" type="submit">Confirm Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
