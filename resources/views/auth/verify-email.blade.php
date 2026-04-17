@extends('layouts.app')

@section('title', 'Verify Email | LEEMO-PALASADA')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="content-card p-4">
                <p class="section-label">Email Verification</p>
                <h1 class="page-title mb-3">Verify your email address</h1>
                <p class="text-muted">Before continuing, please confirm your email using the link we sent. Need another copy? We can send a fresh verification message.</p>

                <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                    @csrf
                    <button class="btn btn-brand" type="submit">Resend Verification Email</button>
                </form>
            </div>
        </div>
    </div>
@endsection
