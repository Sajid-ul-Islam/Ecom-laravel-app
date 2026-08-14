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
    <!-- Google Fonts: Outfit, Plus Jakarta Sans & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">


    <!-- Custom Theme Styles -->
    <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">
    <link href="{{ asset('css/woocommerce-dashboard.css') }}" rel="stylesheet">

    <!-- Immediate Theme Restoration Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('deen_theme') || 'denim';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
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
                    <span class="deen-leather-patch ms-1">Denim Apparel</span>
                </a>


                <button class="navbar-toggler text-white border-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Customer Navigation -->
                    <ul class="navbar-nav me-auto ms-lg-4 gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ url('/') }}"><i class="fas fa-store me-1"></i> Storefront</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('categories*') || request()->is('category*') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('store.categories') }}"><i class="fas fa-tshirt me-1"></i> Categories Directory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('my-account*') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('account.orders') }}"><i class="fas fa-truck-fast me-1 text-success"></i> Track Order</a>
                        </li>
                    </ul>

                    <!-- Right Customer & Admin Access Navigation -->
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <!-- Custom Theme Picker Selector -->
                        <li class="nav-item me-1 dropdown">
                            <button class="deen-theme-picker dropdown-toggle d-flex align-items-center gap-1 border-secondary text-white" type="button" id="themePickerBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined fs-6 text-warning">palette</span>
                                <span id="currentThemeName">Denim Vibe</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border border-secondary" aria-labelledby="themePickerBtn">
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('denim')">
                                        <span class="material-symbols-outlined text-warning fs-5">style</span>
                                        <div>
                                            <div class="fw-bold">13.5oz Washed Denim</div>
                                            <div class="small text-white-50">Authentic indigo twill (Default)</div>
                                        </div>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('dark')">
                                        <span class="material-symbols-outlined text-info fs-5">dark_mode</span>
                                        <div>
                                            <div class="fw-bold">Midnight Studio Dark</div>
                                            <div class="small text-white-50">Luxury obsidian & neon violet</div>
                                        </div>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('neon')">
                                        <span class="material-symbols-outlined text-danger fs-5">bolt</span>
                                        <div>
                                            <div class="fw-bold">Cyberpunk Urban Neon</div>
                                            <div class="small text-white-50">Charcoal & electric pink</div>
                                        </div>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('light')">
                                        <span class="material-symbols-outlined text-primary fs-5">light_mode</span>
                                        <div>
                                            <div class="fw-bold">Studio Minimal Light</div>
                                            <div class="small text-white-50">Clean slate & ocean navy</div>
                                        </div>
                                    </button>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item me-2">
                            <a href="{{ route('admin.analytics') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold">
                                <i class="fas fa-shield-alt me-1"></i> Admin Portal Access
                            </a>
                        </li>

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
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item" href="{{ route('admin.analytics') }}">
                                        <i class="fas fa-chart-line me-2 text-warning"></i> Admin Analytics Hub
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
    <script>
    function changeDeenTheme(themeName) {
        document.documentElement.setAttribute('data-theme', themeName);
        localStorage.setItem('deen_theme', themeName);
        updateThemeLabel(themeName);
    }

    function updateThemeLabel(themeName) {
        const names = {
            denim: 'Washed Denim',
            dark: 'Midnight Dark',
            neon: 'Cyberpunk Neon',
            light: 'Studio Light'
        };
        const el = document.getElementById('currentThemeName');
        if (el) el.innerText = names[themeName] || 'Washed Denim';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('deen_theme') || 'denim';
        updateThemeLabel(saved);
    });
    </script>
</body>
</html>