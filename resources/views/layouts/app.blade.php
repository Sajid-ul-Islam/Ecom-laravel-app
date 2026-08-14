<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Deen Commerce - Retail Fashion & Apparel') }}</title>

    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom Theme Styles -->
    <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">
    <link href="{{ asset('css/woocommerce-dashboard.css') }}" rel="stylesheet">
</head>


<body>
    <div id="app">
        <!-- Top Announcement Bar -->
        <div class="deen-promo-bar">
            <i class="fas fa-truck-fast me-1"></i> FREE SHIPPING ON ORDERS OVER ৳2,000 &bull; NEW SEASON DENIM & URBAN FASHION 2026
        </div>

        <nav class="navbar navbar-expand-lg deen-navbar sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="https://deencommerce.com/wp-content/uploads/2025/04/Deen-Logo-Light-scaled.png" alt="Deen Commerce" style="height: 38px; object-fit: contain;">
                    <span class="deen-brand-badge ms-1">Retail Store</span>
                </a>

                <button class="navbar-toggler text-white border-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Navigation -->
                    <ul class="navbar-nav me-auto ms-lg-4 gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ url('/') }}"><i class="fas fa-store me-1"></i> Storefront</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('woocommerce/products') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('woocommerce.products') }}"><i class="fas fa-tshirt me-1"></i> Fashion Catalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/analytics') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('admin.analytics') }}"><i class="fas fa-chart-pie me-1 text-warning"></i> BI Analytics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('woocommerce/dashboard') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('woocommerce.dashboard') }}"><i class="fas fa-sync-alt me-1 text-primary"></i> WooCommerce Hub</a>
                        </li>
                    </ul>


                    <!-- Right Navigation -->
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1 text-primary"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('account.dashboard') }}">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> My Account Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.orders') }}">
                                        <i class="fas fa-box-open me-2 text-success"></i> Track My Orders
                                    </a>
                                    <a class="dropdown-item" href="{{ route('woocommerce.dashboard') }}">
                                        <i class="fas fa-chart-line me-2 text-info"></i> WooCommerce Hub
                                    </a>
                                    <hr class="dropdown-divider">

                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>