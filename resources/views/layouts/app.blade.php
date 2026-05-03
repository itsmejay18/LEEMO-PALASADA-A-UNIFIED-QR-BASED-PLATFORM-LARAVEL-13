<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-coreui-theme="light">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">

        <title>@yield('title', $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA'))</title>
        @include('partials.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite([
            'resources/css/app.css',
            'resources/vendor/coreui-kit/vendors/simplebar/css/simplebar.css',
            'resources/vendor/coreui-kit/vendors/@coreui/icons/css/free.min.css',
            'resources/vendor/coreui-kit/css/style.css',
            'resources/vendor/coreui-kit/css/examples.css',
            'resources/css/coreui-dashboard.css',
            'resources/vendor/coreui-kit/js/config.js',
            'resources/vendor/coreui-kit/js/color-modes.js',
            'resources/vendor/coreui-kit/vendors/@coreui/coreui/js/coreui.bundle.min.js',
            'resources/vendor/coreui-kit/vendors/simplebar/js/simplebar.min.js',
            'resources/js/app.js',
        ])
    </head>
    @php
        $appName = $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA');
        $pageTitle = trim($__env->yieldContent('title')) ?: $appName;
        $coreuiIconPath = Vite::asset('resources/vendor/coreui-kit/vendors/@coreui/icons/svg/free.svg');
        $avatarPath = auth()->user()->profile_photo_path
            ? asset('storage/'.auth()->user()->profile_photo_path)
            : Vite::asset('resources/img/favicon.png');
    @endphp
    <body class="market-app dashboard-shell">
        @include('layouts.sidebar')

        <div class="wrapper d-flex flex-column min-vh-100">
            <header class="header header-sticky p-0 mb-4">
                <div class="container-fluid border-bottom px-4">
                    <button
                        class="header-toggler"
                        type="button"
                        onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).toggle()"
                        style="margin-inline-start: -14px;"
                        aria-label="Toggle sidebar navigation"
                    >
                        <i class="icon icon-lg cil-menu"></i>
                    </button>

                    <ul class="header-nav d-none d-lg-flex">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route(auth()->user()->dashboardRoute()) }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('map.index') }}">Market Map</a>
                        </li>
                    </ul>

                    <ul class="header-nav ms-auto d-none d-sm-flex">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="icon icon-lg cil-bell"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="icon icon-lg cil-list-rich"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="icon icon-lg cil-envelope-open"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="header-nav">
                        <li class="nav-item py-1">
                            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                        </li>
                        <li class="nav-item dropdown">
                            <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button" aria-expanded="false" data-coreui-toggle="dropdown" aria-label="Toggle light and dark theme">
                                <i class="icon icon-lg theme-icon-active-font cil-contrast"></i>
                                <svg class="theme-icon-active d-none" aria-hidden="true">
                                    <use xlink:href="{{ $coreuiIconPath }}#cil-contrast"></use>
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                                <li>
                                    <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                                        <i class="icon icon-lg me-3 cil-sun"></i>
                                        <svg class="d-none" aria-hidden="true">
                                            <use xlink:href="{{ $coreuiIconPath }}#cil-sun"></use>
                                        </svg>
                                        Light
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                                        <i class="icon icon-lg me-3 cil-moon"></i>
                                        <svg class="d-none" aria-hidden="true">
                                            <use xlink:href="{{ $coreuiIconPath }}#cil-moon"></use>
                                        </svg>
                                        Dark
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                                        <i class="icon icon-lg me-3 cil-contrast"></i>
                                        <svg class="d-none" aria-hidden="true">
                                            <use xlink:href="{{ $coreuiIconPath }}#cil-contrast"></use>
                                        </svg>
                                        Auto
                                    </button>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item py-1">
                            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                        </li>
                        <li class="nav-item d-none d-md-flex align-items-center">
                            <span class="nav-link">{{ auth()->user()->primaryRole() ?? 'User' }}</span>
                        </li>
                        <li class="nav-item py-1 d-none d-md-block">
                            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md">
                                    <img class="avatar-img" src="{{ $avatarPath }}" alt="{{ auth()->user()->email }}">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end pt-0">
                                <div class="dropdown-header bg-body-tertiary text-body-secondary rounded-top mb-2">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <div class="small">{{ auth()->user()->email }}</div>
                                </div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="icon me-2 cil-user"></i>
                                    Profile
                                </a>
                                @if(auth()->user()->hasRole('Admin'))
                                    <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                        <i class="icon me-2 cil-settings"></i>
                                        Settings
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="icon me-2 cil-account-logout"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid px-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb my-0">
                            <li class="breadcrumb-item"><a href="{{ route(auth()->user()->dashboardRoute()) }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                        </ol>
                    </nav>
                </div>
            </header>

            <main class="body flex-grow-1">
                <div class="container-lg px-4 dashboard-content">
                    @include('partials.alerts')
                    @yield('content')
                </div>
            </main>

            <footer class="footer px-4">
                <div>{{ $appName }}</div>
                <div class="ms-auto">Powered by CoreUI UI Components</div>
            </footer>
        </div>
    </body>
</html>
