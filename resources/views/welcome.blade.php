@extends('layouts.landing')

@section('title', 'LEEMO-Palasada')

@section('content')
    @php
        $landingBranding = Vite::asset('resources/img/landinbranding.png');
        $startUrl = auth()->check() ? route(auth()->user()->dashboardRoute()) : route('register');
        $supportEmail = $appSettings['support_email'] ?? null;
        $contactUrl = filled($supportEmail) ? 'mailto:'.$supportEmail : '#contact';

        $features = [
            [
                'icon' => 'cil-building',
                'tone' => 'primary',
                'title' => 'Smart Stall Management',
                'content' => 'Monitor stall occupancy, contracts, and vendor information in real time.',
            ],
            [
                'icon' => 'cil-qr-code',
                'tone' => 'success',
                'title' => 'QR-Based Payment System',
                'content' => 'Record and track rental payments instantly using QR code scanning, supporting both cash and digital payments.',
            ],
            [
                'icon' => 'cil-map',
                'tone' => 'info',
                'title' => 'Interactive Market Navigation',
                'content' => 'Help customers easily find stalls using a digital map with a "You Are Here" feature.',
            ],
            [
                'icon' => 'cil-search',
                'tone' => 'warning',
                'title' => 'Product Search',
                'content' => 'Locate products like fruits, vegetables, or meat and instantly see which stalls offer them.',
            ],
            [
                'icon' => 'cil-speedometer',
                'tone' => 'secondary',
                'title' => 'Real-Time Dashboards',
                'content' => 'Provide managers and treasurers with live data for better decision-making and monitoring.',
            ],
        ];

        $roles = [
            [
                'icon' => 'cil-people',
                'title' => 'Administrators (Managers & Treasurer)',
                'content' => 'Manage contracts, monitor payments, and oversee market operations through centralized dashboards.',
            ],
            [
                'icon' => 'cil-mobile',
                'title' => 'Collectors',
                'content' => 'Use mobile devices to scan QR codes, collect payments, and automatically update records.',
            ],
            [
                'icon' => 'cil-basket',
                'title' => 'Renters (Vendors)',
                'content' => 'Track rental status, view payment history, and receive reminders for contract renewals.',
            ],
            [
                'icon' => 'cil-cart',
                'title' => 'Customers',
                'content' => 'Scan QR codes, view stall details, search for products, and navigate the market with ease.',
            ],
        ];

        $benefits = [
            'Eliminates manual paperwork',
            'Reduces payment errors and disputes',
            'Provides real-time monitoring',
            'Enhances customer experience',
            'Promotes transparency and accountability',
        ];

        $steps = [
            'Scan a stall QR code',
            'View stall details instantly',
            'Make or record payments',
            'Access real-time dashboards',
            'Navigate the market with ease',
        ];
    @endphp

    <section class="landing-hero mb-5">
        <div class="landing-hero-content">
            <p class="section-label">LEEMO-Palasada</p>
            <h1 class="display-4 fw-semibold mb-3">Transforming Public Market Management Through Smart QR Technology</h1>
            <p class="lead text-body-secondary mb-4">LEEMO-Palasada is a unified web and mobile platform that streamlines stall management, digitizes payment collection, and enhances customer navigation in public markets.</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ $startUrl }}" class="btn btn-primary btn-lg">
                    <i class="icon me-2 cil-paper-plane"></i>
                    Get Started
                </a>
                <a href="{{ route('map.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="icon me-2 cil-qr-code"></i>
                    Scan &amp; Explore
                </a>
            </div>
        </div>
        <div class="landing-hero-media">
            <img src="{{ $landingBranding }}" alt="LEEMO-Palasada branding" class="landing-hero-image">
        </div>
    </section>

    <section id="about" class="landing-section">
        <div class="row align-items-start g-4">
            <div class="col-lg-4">
                <p class="section-label">About the System</p>
                <h2 class="page-title mb-0">What is LEEMO-Palasada?</h2>
            </div>
            <div class="col-lg-8">
                <p class="fs-5 text-body-secondary">LEEMO-Palasada is a QR-based digital platform designed to modernize public market operations. It connects market administrators, vendors, collectors, and customers into one centralized system, replacing manual processes with efficient, real-time digital solutions.</p>
                <p class="fs-5 text-body-secondary mb-0">By integrating stall management, payment tracking, and interactive navigation, the system improves transparency, reduces errors, and enhances the overall market experience.</p>
            </div>
        </div>
    </section>

    <section id="features" class="landing-section">
        <div class="landing-section-header">
            <p class="section-label">Key Features</p>
            <h2 class="page-title mb-0">Key Features</h2>
        </div>
        <div class="row g-4">
            @foreach ($features as $feature)
                <div class="col-md-6 col-xl-4">
                    <article class="card landing-card h-100">
                        <div class="card-body">
                            <span class="landing-icon text-bg-{{ $feature['tone'] }} mb-4">
                                <i class="icon icon-lg {{ $feature['icon'] }}"></i>
                            </span>
                            <h3 class="h5 card-title">{{ $feature['title'] }}</h3>
                            <p class="card-text text-body-secondary mb-0">{{ $feature['content'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    <section id="roles" class="landing-section">
        <div class="landing-section-header">
            <p class="section-label">User Roles</p>
            <h2 class="page-title mb-0">Designed for Every Market User</h2>
        </div>
        <div class="row g-4">
            @foreach ($roles as $role)
                <div class="col-md-6">
                    <article class="card landing-card h-100">
                        <div class="card-body d-flex gap-3">
                            <span class="landing-icon bg-primary-subtle text-primary-emphasis">
                                <i class="icon icon-lg {{ $role['icon'] }}"></i>
                            </span>
                            <div>
                                <h3 class="h5 card-title">{{ $role['title'] }}</h3>
                                <p class="card-text text-body-secondary mb-0">{{ $role['content'] }}</p>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    <section id="why" class="landing-section">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <p class="section-label">Why Choose LEEMO-Palasada?</p>
                <h2 class="page-title mb-3">Why Choose Our System?</h2>
                <p class="text-body-secondary mb-0">A centralized system helps teams reduce manual work, align payment records, and serve customers with clearer information.</p>
            </div>
            <div class="col-lg-7">
                <div class="card landing-card">
                    <div class="card-body">
                        <ul class="landing-check-list mb-0">
                            @foreach ($benefits as $benefit)
                                <li>
                                    <i class="icon cil-check-circle text-success"></i>
                                    <span>{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="landing-section">
        <div class="landing-section-header">
            <p class="section-label">How It Works</p>
            <h2 class="page-title mb-0">How It Works</h2>
        </div>
        <div class="row g-3">
            @foreach ($steps as $index => $step)
                <div class="col-md">
                    <div class="card landing-card h-100">
                        <div class="card-body">
                            <span class="landing-step-number mb-3">{{ $index + 1 }}</span>
                            <p class="fw-semibold mb-0">{{ $step }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="contact" class="landing-section pb-2">
        <div class="landing-cta text-center">
            <p class="section-label text-white-50">Call to Action</p>
            <h2 class="display-6 fw-semibold text-white mb-3">Experience Smarter Market Management Today</h2>
            <p class="lead text-white-50 mb-4">Join the future of public market systems with LEEMO-Palasada. Simplify operations, improve efficiency, and enhance customer satisfaction, all in one platform.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ $startUrl }}" class="btn btn-light btn-lg">
                    <i class="icon me-2 cil-paper-plane"></i>
                    Get Started Now
                </a>
                <a href="{{ $contactUrl }}" class="btn btn-outline-light btn-lg">
                    <i class="icon me-2 cil-phone"></i>
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection
