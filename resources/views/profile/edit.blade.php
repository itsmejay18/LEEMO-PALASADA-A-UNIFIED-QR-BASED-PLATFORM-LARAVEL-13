@extends('layouts.app')

@section('title', 'Profile | LEEMO-PALASADA')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="content-card p-4">
                <p class="section-label">Account Profile</p>
                <h1 class="page-title mb-0">Manage your profile and security settings</h1>
            </div>
        </div>

        <div class="col-lg-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="col-lg-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="col-12">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
