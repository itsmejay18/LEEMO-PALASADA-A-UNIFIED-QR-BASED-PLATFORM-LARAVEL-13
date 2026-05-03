@php
    $user = auth()->user();
    $appName = $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA');
    $sidebarLogo = Vite::asset('resources/img/sidebarlogo-cropped.png');
    $workspaceItems = array_values(array_filter([
        $user->hasRole('Customer') ? [
            'label' => 'Transactions',
            'icon' => 'cil-file',
            'href' => route('customer.transactions'),
            'active' => ['customer.transactions'],
        ] : null,
        $user->hasRole('Customer') ? [
            'label' => 'Bookmarks',
            'icon' => 'cil-bookmark',
            'href' => route('customer.bookmarks'),
            'active' => ['customer.bookmarks'],
        ] : null,
        $user->hasRole('Customer') ? [
            'label' => 'Cart',
            'icon' => 'cil-cart',
            'href' => route('customer.cart'),
            'active' => ['customer.cart', 'customer.checkout', 'customer.checkout.store'],
        ] : null,
        $user->hasRole('Vendor') ? [
            'label' => 'Products',
            'icon' => 'cil-basket',
            'href' => route('vendor.products.index'),
            'active' => ['vendor.products.*'],
        ] : null,
        $user->hasRole('Vendor') && $user->vendor ? [
            'label' => 'Public Profile',
            'icon' => 'cil-building',
            'href' => route('vendors.show', $user->vendor),
            'active' => ['vendors.show'],
        ] : null,
        $user->hasRole('Collector') ? [
            'label' => 'Collections',
            'icon' => 'cil-money',
            'href' => route('collector.collections.index'),
            'active' => ['collector.collections.*'],
        ] : null,
        $user->hasRole('Collector') ? [
            'label' => 'Unpaid Route Map',
            'icon' => 'cil-map',
            'href' => route('map.index', ['payment' => 'unpaid']),
            'active' => ['map.*'],
        ] : null,
        $user->hasRole('Treasurer') ? [
            'label' => 'Verification',
            'icon' => 'cil-check-circle',
            'href' => route('treasurer.collections.index'),
            'active' => ['treasurer.collections.*', 'treasurer.records.*', 'treasurer.receipts.*'],
        ] : null,
        $user->hasRole('Treasurer') ? [
            'label' => 'Compliance Map',
            'icon' => 'cil-map',
            'href' => route('map.index', ['payment' => 'unpaid']),
            'active' => ['map.*'],
        ] : null,
        $user->hasRole('Manager') ? [
            'label' => 'Vendors',
            'icon' => 'cil-people',
            'href' => route('manager.vendors.index'),
            'active' => ['manager.vendors.*'],
        ] : null,
        $user->hasRole('Manager') ? [
            'label' => 'Expiring Contracts',
            'icon' => 'cil-calendar',
            'href' => route('map.index', ['contract' => 'expiring']),
            'active' => ['map.*'],
        ] : null,
        $user->hasRole('Manager') ? [
            'label' => 'Reports',
            'icon' => 'cil-chart',
            'href' => route('manager.reports'),
            'active' => ['manager.reports'],
        ] : null,
        $user->hasRole('Admin') ? [
            'label' => 'Users',
            'icon' => 'cil-user',
            'href' => route('admin.users'),
            'active' => ['admin.users', 'admin.users.*'],
        ] : null,
        $user->hasRole('Admin') ? [
            'label' => 'Settings',
            'icon' => 'cil-settings',
            'href' => route('admin.settings'),
            'active' => ['admin.settings', 'admin.settings.*'],
        ] : null,
    ]));

    $navGroups = [
        [
            'label' => 'Main',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'cil-speedometer',
                    'href' => route($user->dashboardRoute()),
                    'active' => ['dashboard', '*.dashboard'],
                ],
                [
                    'label' => 'Market Map',
                    'icon' => 'cil-location-pin',
                    'href' => route('map.index'),
                    'active' => ['map.*'],
                ],
            ],
        ],
        [
            'label' => 'QR Tools',
            'items' => [
                [
                    'label' => 'QR Scanner',
                    'icon' => 'cil-qr-code',
                    'href' => route('qr.scanner'),
                    'active' => ['qr.scanner'],
                ],
                [
                    'label' => 'QR Generator',
                    'icon' => 'cil-print',
                    'href' => route('qr.generator'),
                    'active' => ['qr.generator', 'qr.products.*', 'qr.locations.*'],
                ],
            ],
        ],
        [
            'label' => 'Workspace',
            'items' => $workspaceItems,
        ],
        [
            'label' => 'Account',
            'items' => [
                [
                    'label' => 'Profile',
                    'icon' => 'cil-user',
                    'href' => route('profile.edit'),
                    'active' => ['profile.*'],
                ],
            ],
        ],
    ];
@endphp

<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom justify-content-center" style="min-height: 120px; padding: 1rem;">
        <a class="sidebar-brand text-decoration-none" href="{{ route('landing') }}">
            <img class="sidebar-brand-full" src="{{ $sidebarLogo }}" alt="{{ $appName }}" style="height: 96px; max-width: 100%; object-fit: contain;">
            <img class="sidebar-brand-narrow" src="{{ $sidebarLogo }}" alt="{{ $appName }}" style="height: 54px; width: 54px; object-fit: cover; object-position: left center;">
        </a>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close sidebar" onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).toggle()"></button>
    </div>

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        @foreach($navGroups as $group)
            @if(count($group['items']))
                <li class="nav-title">{{ $group['label'] }}</li>

                @foreach($group['items'] as $item)
                    @php
                        $isActive = request()->routeIs(...$item['active']);
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $item['href'] }}">
                            <i class="nav-icon {{ $item['icon'] }}"></i>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            @endif
        @endforeach

        <li class="nav-divider"></li>
        <li class="nav-title">Session</li>
        <li class="nav-item mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link w-100 border-0" type="submit">
                    <i class="nav-icon cil-account-logout"></i>
                    Logout
                </button>
            </form>
        </li>
    </ul>

    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable" aria-label="Collapse sidebar"></button>
    </div>
</div>
