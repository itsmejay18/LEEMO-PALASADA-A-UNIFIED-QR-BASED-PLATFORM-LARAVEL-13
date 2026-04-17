@php
    $user = auth()->user();
    $appName = $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA');
    $workspaceItems = array_values(array_filter([
        $user->hasRole('Customer') ? [
            'label' => 'Transactions',
            'meta' => 'Orders and payment history',
            'icon' => 'bi-receipt',
            'href' => route('customer.transactions'),
            'active' => ['customer.transactions'],
        ] : null,
        $user->hasRole('Customer') ? [
            'label' => 'Bookmarks',
            'meta' => 'Saved vendor shortcuts',
            'icon' => 'bi-bookmark-heart',
            'href' => route('customer.bookmarks'),
            'active' => ['customer.bookmarks'],
        ] : null,
        $user->hasRole('Customer') ? [
            'label' => 'Cart',
            'meta' => 'Checkout and item review',
            'icon' => 'bi-cart3',
            'href' => route('customer.cart'),
            'active' => ['customer.cart', 'customer.checkout', 'customer.checkout.store'],
        ] : null,
        $user->hasRole('Vendor') ? [
            'label' => 'Products',
            'meta' => 'Inventory and QR assets',
            'icon' => 'bi-box-seam',
            'href' => route('vendor.products.index'),
            'active' => ['vendor.products.*'],
        ] : null,
        $user->hasRole('Vendor') && $user->vendor ? [
            'label' => 'Public Profile',
            'meta' => 'Vendor storefront preview',
            'icon' => 'bi-shop',
            'href' => route('vendors.show', $user->vendor),
            'active' => ['vendors.show'],
        ] : null,
        $user->hasRole('Collector') ? [
            'label' => 'Collections',
            'meta' => 'Vendor dues and proofs',
            'icon' => 'bi-cash-stack',
            'href' => route('collector.collections.index'),
            'active' => ['collector.collections.*'],
        ] : null,
        $user->hasRole('Treasurer') ? [
            'label' => 'Verification',
            'meta' => 'Receipts and audits',
            'icon' => 'bi-patch-check',
            'href' => route('treasurer.collections.index'),
            'active' => ['treasurer.collections.*', 'treasurer.records.*', 'treasurer.receipts.*'],
        ] : null,
        $user->hasRole('Manager') ? [
            'label' => 'Vendors',
            'meta' => 'Stalls and assignments',
            'icon' => 'bi-people',
            'href' => route('manager.vendors.index'),
            'active' => ['manager.vendors.*'],
        ] : null,
        $user->hasRole('Manager') ? [
            'label' => 'Reports',
            'meta' => 'Market analytics and trends',
            'icon' => 'bi-bar-chart-line',
            'href' => route('manager.reports'),
            'active' => ['manager.reports'],
        ] : null,
        $user->hasRole('Admin') ? [
            'label' => 'Users',
            'meta' => 'Accounts and role control',
            'icon' => 'bi-person-gear',
            'href' => route('admin.users'),
            'active' => ['admin.users', 'admin.users.*'],
        ] : null,
        $user->hasRole('Admin') ? [
            'label' => 'Settings',
            'meta' => 'Platform configuration',
            'icon' => 'bi-sliders',
            'href' => route('admin.settings'),
            'active' => ['admin.settings', 'admin.settings.*'],
        ] : null,
    ]));

    $navGroups = [
        [
            'label' => 'Explore',
            'items' => [
                [
                    'label' => 'Home',
                    'meta' => 'Landing and featured stalls',
                    'icon' => 'bi-house-door',
                    'href' => route('landing'),
                    'active' => ['landing'],
                ],
                [
                    'label' => 'Market Map',
                    'meta' => 'Wayfinding and stall search',
                    'icon' => 'bi-geo-alt',
                    'href' => route('map.index'),
                    'active' => ['map.*'],
                ],
                [
                    'label' => 'Dashboard',
                    'meta' => 'Role overview and KPIs',
                    'icon' => 'bi-speedometer2',
                    'href' => route($user->dashboardRoute()),
                    'active' => ['dashboard', '*.dashboard'],
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
                    'meta' => 'Personal settings and security',
                    'icon' => 'bi-person-circle',
                    'href' => route('profile.edit'),
                    'active' => ['profile.*'],
                ],
            ],
        ],
    ];
@endphp

<div class="sidebar-backdrop" data-dashboard-sidebar-close></div>

<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="dashboard-sidebar-panel">
        <div class="sidebar-header">
            <a class="sidebar-brand" href="{{ route('landing') }}">
                <span class="brand-mark">LP</span>
                <span class="sidebar-brand-copy">
                    <span class="brand-title d-block">{{ $appName }}</span>
                    <small class="brand-subtitle d-block">Unified QR Market Platform</small>
                </span>
            </a>

            <button class="sidebar-close" type="button" data-dashboard-sidebar-close aria-label="Close sidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-user-card">
            <span class="sidebar-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</span>

            <div class="sidebar-user-copy">
                <span class="sidebar-user-name">{{ $user->name }}</span>
                <small class="sidebar-user-email">{{ $user->email }}</small>
                <span class="badge-soft mt-2">{{ $user->primaryRole() ?? 'User' }}</span>
            </div>
        </div>

        <div class="sidebar-nav-stack">
            @foreach($navGroups as $group)
                @if(count($group['items']))
                    <section class="sidebar-section">
                        <p class="sidebar-section-title">{{ $group['label'] }}</p>

                        <nav class="sidebar-nav">
                            @foreach($group['items'] as $item)
                                @php
                                    $isActive = request()->routeIs(...$item['active']);
                                @endphp

                                <a class="sidebar-nav-link {{ $isActive ? 'active' : '' }}" href="{{ $item['href'] }}">
                                    <span class="sidebar-icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>

                                    <span class="sidebar-nav-copy">
                                        <span class="sidebar-nav-label">{{ $item['label'] }}</span>
                                        <small class="sidebar-nav-meta">{{ $item['meta'] }}</small>
                                    </span>
                                </a>
                            @endforeach
                        </nav>
                    </section>
                @endif
            @endforeach
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="sidebar-nav-link sidebar-logout" type="submit">
                    <span class="sidebar-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </span>

                    <span class="sidebar-nav-copy">
                        <span class="sidebar-nav-label">Logout</span>
                        <small class="sidebar-nav-meta">End your current session</small>
                    </span>
                </button>
            </form>
        </div>
    </div>
</aside>
