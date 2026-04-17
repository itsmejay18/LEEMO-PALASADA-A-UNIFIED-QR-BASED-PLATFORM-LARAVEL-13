<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|outfit:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $appName = $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA');
        $pageTitle = trim($__env->yieldContent('title')) ?: $appName;
    @endphp
    <body class="market-app dashboard-shell">
        <div class="dashboard-layout" data-dashboard-layout>
            @include('layouts.sidebar')

            <div class="dashboard-main">
                <header class="dashboard-topbar">
                    <div class="d-flex align-items-center gap-3">
                        <button
                            class="btn btn-outline-brand sidebar-toggle"
                            type="button"
                            data-dashboard-sidebar-toggle
                            aria-controls="dashboardSidebar"
                            aria-expanded="true"
                            aria-label="Toggle sidebar navigation"
                        >
                            <i class="bi bi-list"></i>
                        </button>

                        <div>
                            <span class="section-label mb-1 d-inline-block">Dashboard Workspace</span>
                            <h1 class="dashboard-shell-title mb-0">{{ $pageTitle }}</h1>
                        </div>
                    </div>

                    <div class="dashboard-topbar-meta">
                        <span class="badge-soft">{{ auth()->user()->primaryRole() ?? 'User' }}</span>

                        <div class="text-end">
                            <span class="dashboard-topbar-user">{{ auth()->user()->name }}</span>
                            <small class="dashboard-topbar-subtitle d-block">{{ $appName }}</small>
                        </div>
                    </div>
                </header>

                <main class="page-shell dashboard-content">
                    @include('partials.alerts')
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
