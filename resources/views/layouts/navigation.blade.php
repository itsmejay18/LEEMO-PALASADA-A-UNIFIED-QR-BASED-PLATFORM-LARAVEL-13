@php
    $appName = $appSettings['market_name'] ?? config('app.name', 'LEEMO-PALASADA');
    $faviconPath = Vite::asset('resources/img/favicon.png');
@endphp

<nav class="navbar navbar-expand-lg app-navbar sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('landing') }}">
            <img class="brand-mark brand-mark-img" src="{{ $faviconPath }}" alt="{{ $appName }} icon" width="48" height="48">
            <span>
                <span class="brand-title">{{ $appName }}</span>
                <small class="brand-subtitle d-block">Unified QR Market Platform</small>
            </span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-coreui-toggle="collapse" data-coreui-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="icon icon-lg cil-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map.*') ? 'active' : '' }}" href="{{ route('map.index') }}">Market Map</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}" href="{{ route(auth()->user()->dashboardRoute()) }}">Dashboard</a>
                    </li>

                    @if(auth()->user()->hasRole('Customer'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.transactions') ? 'active' : '' }}" href="{{ route('customer.transactions') }}">Transactions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.bookmarks') ? 'active' : '' }}" href="{{ route('customer.bookmarks') }}">Bookmarks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}">Cart</a>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('Vendor'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}">Products</a>
                        </li>
                        @if(auth()->user()->vendor)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('vendors.show') ? 'active' : '' }}" href="{{ route('vendors.show', auth()->user()->vendor) }}">Public Profile</a>
                            </li>
                        @endif
                    @endif

                    @if(auth()->user()->hasRole('Collector'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('collector.collections.*') ? 'active' : '' }}" href="{{ route('collector.collections.index') }}">Collections</a>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('Treasurer'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('treasurer.collections.*') ? 'active' : '' }}" href="{{ route('treasurer.collections.index') }}">Verification</a>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('Manager'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.vendors.*') ? 'active' : '' }}" href="{{ route('manager.vendors.index') }}">Vendors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.reports') ? 'active' : '' }}" href="{{ route('manager.reports') }}">Reports</a>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">Settings</a>
                        </li>
                    @endif

                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <span class="badge-soft">{{ auth()->user()->primaryRole() ?? 'User' }}</span>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-brand ms-lg-3 mt-2 mt-lg-0" href="{{ route('register') }}">Create Customer Account</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
