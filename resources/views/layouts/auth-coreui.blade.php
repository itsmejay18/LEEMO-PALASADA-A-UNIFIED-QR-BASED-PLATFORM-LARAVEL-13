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
            'resources/vendor/coreui-kit/vendors/@coreui/coreui/js/coreui.bundle.min.js',
            'resources/vendor/coreui-kit/vendors/simplebar/js/simplebar.min.js',
            'resources/js/app.js',
        ])
    </head>
    <body class="auth-coreui-shell">
        @yield('content')
    </body>
</html>
