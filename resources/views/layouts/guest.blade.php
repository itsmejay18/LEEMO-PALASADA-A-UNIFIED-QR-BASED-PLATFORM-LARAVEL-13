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
    <body class="market-app guest-mode">
        @hasSection('hide_navigation')
        @else
            @include('layouts.navigation')
        @endif

        <main class="page-shell">
            <div class="container py-4">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>
    </body>
</html>
