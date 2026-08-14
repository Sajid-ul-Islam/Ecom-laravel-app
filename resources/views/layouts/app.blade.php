<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- CSRF Token -->
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>{{ config('app.name', 'DEEN.im — Bangladesh\'s Premier Denim & Urban Apparel') }}</title>

 <!-- Bootstrap 5 CSS & FontAwesome -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 <!-- Google Fonts: Outfit, Plus Jakarta Sans & Material Symbols -->
 <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">


 <!-- Custom Theme Styles -->
 <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">
 <link href="{{ asset('css/woocommerce-dashboard.css') }}" rel="stylesheet">

 <!-- PWA Web App Manifest & Service Worker Meta -->
 <link rel="manifest" href="{{ asset('manifest.json') }}">
 <meta name="theme-color" content="#0b132b">
 <meta name="mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

 <!-- Immediate Theme Restoration Script -->
 <script>
 (function() {
 const savedTheme = localStorage.getItem('deen_theme') || 'denim';
 document.documentElement.setAttribute('data-theme', savedTheme);
 })();
 </script>
</head>



<body>
 <!-- Skip to Main Content (Accessibility) -->
 <a href="#main-content" class="deen-skip-link">Skip to main content</a>

 <div id="app">
        <!-- Top Announcement Bar with DEEN.im Privileges & Logo -->
        <div class="deen-promo-bar">
            <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 text-center text-md-start">
                <div class="d-inline-flex align-items-center gap-2 mx-auto mx-md-0">
                    <img src="{{ asset('images/deen-logo-dark.png') }}" alt="DEEN" style="height: 18px; width: auto; object-fit: contain; filter: brightness(1.2);" onerror="this.src='https://deencommerce.com/wp-content/uploads/2025/04/cropped-Deen-Logo-scaled-1.png'">
                    <span class="badge bg-warning text-dark font-monospace fw-bold" style="font-size: 0.68rem; padding: 2px 7px;">DEEN.im VIP</span>
                    <span class="d-inline-flex align-items-center gap-1.5"><i class="fas fa-truck-fast text-warning"></i> FREE EXPRESS SHIPPING OVER ৳2,000 &bull; 13.5OZ AUTHENTIC RAW DENIM 2026</span>
                    <span class="badge deen-flash-timer font-monospace fw-bold d-none d-sm-inline-flex align-items-center gap-1" style="font-size: 0.65rem; padding: 2px 8px;"><i class="fas fa-fire" aria-hidden="true"></i> <span id="flashSaleTimer" aria-live="off">ENDS IN 05h : 42m : 18s</span></span>
                </div>
                <div class="d-none d-md-flex align-items-center gap-3 ms-auto" style="font-size: 0.72rem;">
                    <span><i class="fas fa-shield-halved text-info me-1"></i> 7-Day Fit Guarantee</span>
                    <span>&bull;</span>
                    <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="text-white-50 text-decoration-none hover-white">
                        <i class="fab fa-telegram text-warning me-1"></i> VIP Concierge: @DEEN_Commerce_bot
                    </a>
                    <span>&bull;</span>
                    <span class="badge bg-secondary bg-opacity-40 text-white border border-secondary px-2 py-0.5" style="font-size: 0.68rem;">BDT (৳)</span>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg deen-navbar sticky-top">
            <div class="container">
                <!-- Brand Identity: Pure Typographic DEEN.im Titlebar -->
                <a class="navbar-brand d-flex flex-column text-decoration-none" href="{{ url('/') }}" title="DEEN.im — Bangladesh's Premier Denim & Urban Apparel">
                    <div class="deen-brand-lockup d-flex align-items-baseline">
                        <span class="deen-brand-text">DEEN</span>
                        <span class="deen-domain-badge"><span class="deen-domain-dot">.</span>im</span>
                    </div>
                    <span class="deen-brand-subtitle">RAW DENIM & APPAREL</span>
                </a>

                <!-- Mobile Header Direct Action Buttons (Search, Wishlist, Cart, Account, Toggle) -->
                <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
                    <button class="deen-header-icon-btn" onclick="openMobileSearchModal()" title="Search products" aria-label="Search products">
                        <span class="material-symbols-outlined fs-5">search</span>
                    </button>
                    <button class="deen-header-icon-btn position-relative" onclick="openWishlistModal()" title="Saved Wishlist" aria-label="Saved Wishlist">
                        <span class="material-symbols-outlined fs-5 text-danger">favorite</span>
                        <span class="deen-header-badge bg-danger" id="headerMobileWishlistBadge">0</span>
                    </button>
                    <button class="deen-header-icon-btn position-relative" onclick="openGlobalCartModal()" title="Shopping Cart" aria-label="Shopping Cart">
                        <span class="material-symbols-outlined fs-5 text-white">shopping_cart</span>
                        <span class="deen-header-badge bg-primary" id="headerMobileCartBadge">0</span>
                    </button>
                    <button class="deen-header-icon-btn" onclick="openMobileAccountModal()" title="Client Account" aria-label="Client Account">
                        <span class="material-symbols-outlined fs-5">person</span>
                    </button>
                </div>

                <button class="navbar-toggler text-white border-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Customer Navigation: Clean Storefront & Collections -->
                    <ul class="navbar-nav me-auto ms-lg-4 gap-1 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ url('/') }}" {{ request()->is('/') ? 'aria-current="page"' : '' }}>
                                <span class="material-symbols-outlined nav-icon">storefront</span>
                                <span>Storefront</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('categories*') || request()->is('category*') ? 'active text-white fw-bold' : 'text-white-50' }}" href="{{ route('store.categories') }}" id="collectionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined nav-icon">grid_view</span>
                                <span>Collections</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark shadow-lg border border-secondary p-2" aria-labelledby="collectionsDropdown" style="min-width: 230px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('store.categories') }}">
                                        <span class="material-symbols-outlined text-warning fs-5">apps</span>
                                        <div>
                                            <div class="fw-bold small">All Categories</div>
                                            <div class="text-white-50" style="font-size: 0.70rem;">Browse full catalog showcase</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary my-1"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('store.categories') }}">
                                        <span class="material-symbols-outlined text-primary fs-5">styler</span>
                                        <div>
                                            <div class="fw-bold small">Raw Washed Denim</div>
                                            <div class="text-white-50" style="font-size: 0.70rem;">13.5oz ring-spun cotton jeans</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('store.categories') }}">
                                        <span class="material-symbols-outlined text-success fs-5">apparel</span>
                                        <div>
                                            <div class="fw-bold small">Oxford Button-Downs</div>
                                            <div class="text-white-50" style="font-size: 0.70rem;">Urban tailored slim shirts</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('store.categories') }}">
                                        <span class="material-symbols-outlined text-info fs-5">dry_cleaning</span>
                                        <div>
                                            <div class="fw-bold small">Outerwear & Jackets</div>
                                            <div class="text-white-50" style="font-size: 0.70rem;">Selvedge denim jackets</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Center Instant Search Capsule (Desktop) -->
                    <div class="deen-search-pill d-none d-lg-flex me-3" onclick="openMobileSearchModal()" role="button" tabindex="0" title="Instant Search (Press Ctrl + K)">
                        <span class="material-symbols-outlined fs-5 text-warning">search</span>
                        <span class="placeholder-text">Search denim, shirts, jackets...</span>
                        <kbd class="deen-kbd">Ctrl K</kbd>
                    </div>

                    <!-- Right Customer Actions with Clean Luxury Toolbar -->
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <!-- Custom Theme Picker Selector -->
                        <li class="nav-item dropdown">
                            <button class="deen-theme-picker dropdown-toggle d-flex align-items-center gap-1.5 border-secondary text-white" type="button" id="themePickerBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Change Theme Mode">
                                <span class="material-symbols-outlined fs-6 text-warning">palette</span>
                                <span id="currentThemeName" class="d-none d-xl-inline">Denim Vibe</span>
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
                                    <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('glass')">
                                        <span class="material-symbols-outlined text-info fs-5">blur_on</span>
                                        <div>
                                            <div class="fw-bold">Crystal Glassmorphism</div>
                                            <div class="small text-white-50">Frosted aurora & translucent glass</div>
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

                        <!-- Saved Wishlist Icon Button -->
                        <li class="nav-item">
                            <button type="button" class="deen-header-icon-btn position-relative" onclick="openWishlistModal()" title="Saved Wishlist" aria-label="Saved Wishlist">
                                <span class="material-symbols-outlined fs-5 text-danger">favorite</span>
                                <span class="deen-header-badge bg-danger" id="navWishlistCount">0</span>
                            </button>
                        </li>

                        <!-- Unified Shopping Cart Pill Button -->
                        <li class="nav-item">
                            <button type="button" class="deen-cart-pill-btn" onclick="openGlobalCartModal()" title="Shopping Cart" aria-label="Shopping Cart">
                                <span class="material-symbols-outlined fs-5">shopping_cart</span>
                                <span class="d-none d-md-inline">Cart</span>
                                <span class="deen-cart-count-badge" id="cartCount">0</span>
                            </button>
                        </li>

                        <!-- Admin Portal Link -->
                        <li class="nav-item">
                            <a class="deen-header-icon-btn" href="{{ route('woocommerce.dashboard') }}" title="Admin Portal & WooCommerce Hub" aria-label="Admin Portal">
                                <span class="material-symbols-outlined fs-5 text-warning">admin_panel_settings</span>
                            </a>
                        </li>

                        <!-- Account Navigation Menu (Icon with Dropdown) -->
                        <li class="nav-item dropdown">
                            @guest
                                <button class="deen-header-icon-btn dropdown-toggle dropdown-toggle-no-caret" type="button" id="accountDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Account & Services" aria-label="Account">
                                    <span class="material-symbols-outlined fs-5">person</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border border-secondary p-2" aria-labelledby="accountDropdownBtn" style="min-width: 220px;">
                                    <li class="px-3 py-2 border-bottom border-secondary mb-1">
                                        <div class="fw-bold small text-white">DEEN.im Membership</div>
                                        <div class="small text-white-50" style="font-size: 0.72rem;">Access orders & VIP rewards</div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openAuthModal('login');">
                                            <span class="material-symbols-outlined text-primary fs-5">login</span>
                                            <span>Sign In</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openAuthModal('register');">
                                            <span class="material-symbols-outlined text-warning fs-5">person_add</span>
                                            <span>Join VIP Club</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider border-secondary my-1"></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openOrderTrackModal();">
                                            <span class="material-symbols-outlined text-success fs-5">local_shipping</span>
                                            <span>Track Order</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openWishlistModal();">
                                            <span class="material-symbols-outlined text-danger fs-5">favorite</span>
                                            <span>Saved Wishlist</span>
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <button class="deen-account-pill dropdown-toggle dropdown-toggle-no-caret" type="button" id="accountDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    <span class="small fw-bold text-white d-none d-md-inline">{{ Auth::user()->name }}</span>
                                    <span class="material-symbols-outlined fs-6 text-white-50">expand_more</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border border-secondary p-2" aria-labelledby="accountDropdownBtn" style="min-width: 220px;">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('account.dashboard') }}">
                                            <span class="material-symbols-outlined text-primary fs-5">dashboard</span>
                                            <span>My Account Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('account.orders') }}">
                                            <span class="material-symbols-outlined text-success fs-5">local_shipping</span>
                                            <span>Track My Orders</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openWishlistModal();">
                                            <span class="material-symbols-outlined text-danger fs-5">favorite</span>
                                            <span>Saved Wishlist</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="#" onclick="event.preventDefault(); openLoyaltyModal();">
                                            <span class="material-symbols-outlined text-warning fs-5">military_tech</span>
                                            <span>VIP Reward Coins</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider border-secondary my-1"></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2" href="{{ route('admin.analytics') }}">
                                            <span class="material-symbols-outlined text-info fs-5">analytics</span>
                                            <span>Admin Analytics</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider border-secondary my-1"></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger rounded-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="material-symbols-outlined fs-5">logout</span>
                                            <span>Sign Out</span>
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            @endguest
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

 <main id="main-content" tabindex="-1">
 @yield('content')
 </main>

 <!-- Streamlined Modern Footer -->
 <footer class="bg-dark text-white pt-5 pb-4 border-top border-secondary">
 <div class="container">
 <div class="row g-4 mb-4">
 <!-- Col 1: Brand & Slogan -->
 <div class="col-lg-4 col-md-6">
 <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 mb-3 text-decoration-none">
 <img src="{{ asset('images/deen-logo-dark.png') }}" class="deen-brand-logo" alt="DEEN" onerror="this.src='https://deencommerce.com/wp-content/uploads/2025/04/cropped-Deen-Logo-scaled-1.png'">
 <div class="deen-brand-lockup d-flex align-items-baseline">
 <span class="deen-brand-text text-white">DEEN</span>
 <span class="deen-domain-badge"><span class="deen-domain-dot">.</span>im</span>
 </div>
 <span class="badge bg-warning text-dark font-monospace fw-bold ms-1">দেশের প্রথম ডেনিম ব্র্যান্ড</span>
 </a>
 <p class="text-white-50 small mb-3">DEEN.im is Bangladesh's premier denim & lifestyle fashion brand. Crafted with premium 13.5oz stretch denim and cotton comfort weaves.</p>
 <div class="d-flex gap-2"><a href="https://facebook.com/deencommerce" target="_blank" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center deen-avatar-md" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                            <a href="https://instagram.com/deencommerce" target="_blank" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center deen-avatar-md" aria-label="Follow us on Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                            <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center deen-avatar-md" aria-label="Chat with us on Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>
 </div>
 </div>

 <!-- Col 2: Customer Care -->
 <div class="col-lg-2 col-6">
 <h6 class="fw-bold text-white text-uppercase small mb-3">Customer Care</h6>
 <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2 mb-0">
 <li><a href="#" onclick="event.preventDefault(); openOrderTrackModal();" class="text-white-50 text-decoration-none"><i class="fas fa-truck text-warning me-1"></i> Track Order</a></li>
 <li><a href="{{ route('store.checkout') }}" class="text-white-50 text-decoration-none"><i class="fas fa-rotate-left me-1"></i> Easy Returns</a></li>
 <li><a href="#" onclick="event.preventDefault(); openLoyaltyModal();" class="text-white-50 text-decoration-none"><i class="fas fa-coins text-warning me-1"></i> VIP Rewards</a></li>
 </ul>
 </div>

 <!-- Col 3: Fashion Collections -->
 <div class="col-lg-3 col-6">
 <h6 class="fw-bold text-white text-uppercase small mb-3">Fashion Collections</h6>
 <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2 mb-0">
 <li><a href="{{ route('store.categories') }}" class="text-white-50 text-decoration-none">Raw Washed Denim Jeans</a></li>
 <li><a href="{{ route('store.categories') }}" class="text-white-50 text-decoration-none">Urban Slim Oxford Shirts</a></li>
 <li><a href="{{ route('store.categories') }}" class="text-white-50 text-decoration-none">Casual Polo T-Shirts</a></li>
 <li><a href="{{ route('store.categories') }}" class="text-white-50 text-decoration-none">Outerwear & Leather Jackets</a></li>
 </ul>
 </div>

 <!-- Col 4: Customer Support -->
 <div class="col-lg-3 col-md-6">
 <h6 class="fw-bold text-white text-uppercase small mb-3">Customer Support</h6>
 <div class="mb-3">
 <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="btn btn-outline-info btn-sm rounded-pill w-100 fw-bold mb-2">
 <i class="fab fa-telegram me-1"></i> Chat @DEEN_Commerce_bot
 </a>
 <a href="https://wa.me/8801700000000" target="_blank" class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold">
 <i class="fab fa-whatsapp me-1"></i> WhatsApp Support
 </a>
 </div>
 <div class="small text-white-50">
 <i class="fas fa-shield-alt text-success me-1"></i> 256-Bit SSL Secure Checkout
 </div>
 </div>
 </div>

 <div class="border-top border-secondary pt-3 text-center text-white-50 small d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
 <div>&copy; {{ date('Y') }} Deen Commerce (https://deencommerce.com). All Rights Reserved.</div>
 <div class="d-flex gap-3">
 <span class="text-warning fw-bold">bKash</span>
 <span class="text-warning fw-bold">Nagad</span>
 <span class="text-warning fw-bold">Rocket</span>
 <span class="text-info fw-bold">Visa / MasterCard</span>
 <span class="text-success fw-bold">COD</span>
 </div>
 </div>
 </div>
 </footer>

 <!-- STICKY MOBILE BOTTOM THUMB-ZONE NAVIGATION BAR -->
 <div class="deen-mobile-bottom-nav">
 <a href="{{ route('store.index') }}" class="deen-mobile-nav-item {{ request()->is('/') ? 'active' : '' }}" title="Shop" aria-label="Shop">
 <span class="material-symbols-outlined nav-icon">storefront</span>
 <span>Shop</span>
 </a>
 <a href="{{ route('store.categories') }}" class="deen-mobile-nav-item {{ request()->is('categories*') || request()->is('category*') ? 'active' : '' }}" title="Categories" aria-label="Categories">
 <span class="material-symbols-outlined nav-icon">grid_view</span>
 <span>Categories</span>
 </a>
 <a href="#" onclick="event.preventDefault(); openMobileSearchModal();" class="deen-mobile-nav-item" title="Search" aria-label="Search">
 <span class="material-symbols-outlined nav-icon">search</span>
 <span>Search</span>
 </a>
 <a href="#" onclick="event.preventDefault(); openGlobalCartModal();" class="deen-mobile-nav-item" title="Cart" aria-label="Cart">
 <span class="material-symbols-outlined nav-icon">shopping_cart</span>
 <span>Cart</span>
 <span class="deen-mobile-nav-badge" id="bottomNavCartBadge" aria-live="polite" aria-atomic="true">0</span>
 </a>
 <a href="#" onclick="event.preventDefault(); openMobileAccountModal();" class="deen-mobile-nav-item {{ request()->is('my-account*') ? 'active' : '' }}" title="Account" aria-label="Account">
 <span class="material-symbols-outlined nav-icon">person</span>
 <span>Account</span>
 </a>
 </div>

 <!-- TELEGRAM & INSTANT LIVE CHAT ASSISTANT WIDGET (@DEEN_Commerce_bot) -->
 <div class="deen-telegram-widget-wrapper">
 <!-- Popover Card -->
 <div class="deen-telegram-popover" id="telegramChatPopover">
 <div class="deen-telegram-header">
 <div class="d-flex align-items-center gap-2">
 <i class="fab fa-telegram fa-lg text-white"></i>
 <div>
 <div class="fw-bold small">DEEN Instant Support</div>
 <div class="small opacity-75" class="deen-fs-72"><i class="fas fa-circle text-success me-1" class="deen-fs-8"></i> @DEEN_Commerce_bot Online</div>
 </div>
 </div>
 <button type="button" class="btn-close btn-close-white btn-sm" onclick="toggleTelegramChatPopover()"></button>
 </div>
 <div class="deen-telegram-body">
 <div class="deen-telegram-chat-bubble">
 <div class="fw-bold mb-1 text-warning"><i class="fas fa-robot me-1"></i> Assalamu Alaikum!</div>
 Need instant help with order status, sizing, or returns? Choose a quick topic below or chat live via Telegram / WhatsApp!
 </div>

 <!-- Interactive Quick FAQ Options -->
 <div class="d-flex flex-column gap-2 mb-3">
 <button type="button" onclick="openOrderTrackModal(); toggleTelegramChatPopover();" class="deen-chat-faq-btn">
 <i class="fas fa-truck text-warning me-2"></i> 📦 Track My Active Order
 </button>
 <button type="button" onclick="alert('🚚 Standard Delivery: Inside Dhaka ৳70 (24-48 hrs), Outside Dhaka ৳130 (2-3 days). Express Dhaka Same-Day ৳150.');" class="deen-chat-faq-btn">
 <i class="fas fa-motorcycle text-info me-2"></i> 🚚 Shipping & Delivery Rates
 </button>
 <button type="button" onclick="alert('🔄 Deen 7-Day Exchange Guarantee: You can exchange any unworn product with original tags within 7 days!');" class="deen-chat-faq-btn">
 <i class="fas fa-rotate-left text-success me-2"></i> 🔄 7-Day Return & Exchange
 </button>
 </div>

 <div class="d-grid gap-2">
 <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="deen-telegram-btn">
 <i class="fab fa-telegram-plane"></i> Chat on Telegram (@DEEN_Commerce_bot)
 </a>
 <a href="https://wa.me/8801700000000" target="_blank" class="btn btn-sm btn-success w-100 rounded-pill fw-bold text-white">
 <i class="fab fa-whatsapp me-1"></i> Speak with Agent on WhatsApp
 </a>
 </div>
 </div>
 </div>

 <!-- Floating Trigger Button -->
 <button class="deen-telegram-trigger" onclick="toggleTelegramChatPopover()" title="Chat with @DEEN_Commerce_bot on Telegram" aria-label="Open live chat support" aria-expanded="false" aria-controls="telegramChatPopover">
 <i class="fab fa-telegram-plane"></i>
 <span class="deen-telegram-pulse"></span>
 </button>
 </div>
 </div>

 <!-- PREDICTIVE MOBILE SEARCH OVERLAY MODAL -->
 <div class="modal fade deen-mobile-search-modal" id="mobileSearchModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-fullscreen-md-down modal-lg">
 <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
 <div class="modal-header border-bottom border-secondary bg-dark text-white p-3">
 <div class="w-100 me-2 position-relative">
 <div class="input-group">
 <span class="input-group-text bg-secondary border-secondary text-warning">
 <span class="material-symbols-outlined fs-5">search</span>
 </span>
 <input type="text" id="predictiveSearchInput" class="form-control bg-dark text-white border-secondary px-3" placeholder="Type jeans, shirts, polos..." autocomplete="off" onkeyup="handlePredictiveSearch(this.value)">
 <button class="btn btn-outline-secondary text-white-50" type="button" onclick="clearPredictiveSearch()">
 <span class="material-symbols-outlined fs-6">close</span>
 </button>
 </div>
 </div>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4 bg-dark text-white" style="min-height: 350px;">
 <!-- Trending Search Suggestions Chips -->
 <div id="searchTrendingContainer" class="mb-4">
 <h6 class="text-uppercase text-white-50 small fw-bold mb-3">Popular Searches</h6>
 <div class="d-flex flex-wrap gap-2">
 <button type="button" onclick="setPredictiveSearch('Jeans')" class="m3-chip"><span class="material-symbols-outlined fs-6 text-warning">local_offer</span> Denim Jeans</button>
 <button type="button" onclick="setPredictiveSearch('Shirt')" class="m3-chip"><span class="material-symbols-outlined fs-6 text-info">checkroom</span> Oxford Shirts</button>
 <button type="button" onclick="setPredictiveSearch('Jacket')" class="m3-chip"><span class="material-symbols-outlined fs-6 text-danger">bolt</span> Leather Jackets</button>
 <button type="button" onclick="setPredictiveSearch('Polo')" class="m3-chip"><span class="material-symbols-outlined fs-6 text-success">style</span> Polo Shirts</button>
 </div>
 </div>

 <!-- Instant Search Results -->
 <div id="predictiveResultsList" aria-live="polite" aria-atomic="true">
 <div class="text-center py-4 text-white-50">
 <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
 <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- FLOATING CART TOAST NOTIFICATION -->
 <div id="globalCartToast" class="deen-cart-toast d-none">
 <div class="d-flex align-items-center gap-3">
 <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center deen-avatar-lg">
 <i class="fas fa-check"></i>
 </div>
 <div class="flex-grow-1">
 <div class="fw-bold text-white small" id="toastItemTitle">Item added to cart!</div>
 <div class="small text-white-50">Free shipping available</div>
 </div>
 </div>
 <div class="d-flex gap-2 mt-3 pt-2 border-top border-secondary">
 <button onclick="openGlobalCartModal(); document.getElementById('globalCartToast').classList.add('d-none');" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark flex-grow-1">
 View Cart
 </button>
 <button onclick="document.getElementById('globalCartToast').classList.add('d-none');" class="btn btn-outline-light btn-sm rounded-pill fw-bold">
 Continue Shopping
 </button>
 </div>
 </div>

 <!-- GLOBAL SHOPPING CART MODAL -->
 <div class="modal fade" id="globalCartModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-end modal-md">
 <div class="modal-content border-0 shadow-lg rounded-4">
 <div class="modal-header bg-dark text-white border-0">
 <h5 class="modal-title fw-bold"><i class="fas fa-shopping-cart me-2 text-danger"></i> Shopping Cart</h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4" id="globalCartItemsList">
 <div class="text-center py-4 text-muted">
 <i class="fas fa-shopping-cart fa-3x mb-3 opacity-40"></i>
 <p class="mb-0">Your shopping cart is currently empty.</p>
 </div>
 </div>
 <div class="modal-footer border-top p-3 d-flex flex-column gap-2">
 <!-- Coupon Code Input Box -->
 <div class="deen-coupon-box w-100 mb-2">
 <div class="input-group input-group-sm">
 <input type="text" id="cartCouponInput" class="form-control bg-dark text-white border-secondary rounded-start-pill uppercase" placeholder="Coupon Code (e.g. DEEN2026)">
 <button onclick="applyCartCoupon()" class="btn btn-warning rounded-end-pill font-monospace fw-bold text-dark px-3">Apply</button>
 </div>
 <div id="cartCouponMessage" class="small mt-1 text-center font-monospace"></div>
 </div>

 <div class="d-flex justify-content-between w-100 fw-bold fs-5 text-dark">
 <span>Total:</span>
 <span id="globalCartTotal">৳0.00</span>
 </div>
 <a href="{{ route('store.checkout') }}" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold">
 Proceed to Checkout <i class="fas fa-arrow-right ms-1"></i>
 </a>
 </div>
 </div>
 </div>
 <!-- WISHLIST DRAWER MODAL -->
 <div class="modal fade" id="wishlistModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-end modal-md">
 <div class="modal-content border-0 shadow-lg rounded-4">
 <div class="modal-header bg-dark text-white border-0">
 <h5 class="modal-title fw-bold"><i class="fas fa-heart me-2 text-danger"></i> Your Saved Favorites</h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4" id="wishlistItemsList">
 <div class="text-center py-4 text-muted">
 <i class="fas fa-heart fa-3x mb-3 opacity-40 text-danger"></i>
 <p class="mb-0">Your wishlist is empty. Click the heart icon on any product to save it here!</p>
 </div>
 </div>
 <div class="modal-footer border-top p-3">
 <button type="button" class="btn btn-outline-dark w-100 rounded-pill fw-bold" data-bs-dismiss="modal">
 Continue Browsing
 </button>
 </div>
 </div>
 </div>
 </div>

 <!-- IN-APP ORDER TRACKING MODAL -->
 <div class="modal fade" id="orderTrackModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-md">
 <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
 <div class="modal-header border-secondary border-0 pb-0">
 <h5 class="modal-title fw-bold text-white"><i class="fas fa-truck-fast me-2 text-success"></i> Track Your Order Status</h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4">
 <form onsubmit="event.preventDefault(); trackOrderInline();" class="mb-4">
 <label class="form-label text-white-50 small fw-bold">Enter Order ID or Mobile Number</label>
 <div class="input-group">
 <input type="text" id="trackInput" class="form-control bg-secondary bg-opacity-20 text-white border-secondary px-3" placeholder="e.g. #98241 or 01711000000" required>
 <button class="btn btn-success fw-bold px-3" type="submit"><i class="fas fa-search me-1"></i> Track</button>
 </div>
 </form>

 <!-- Order Progress Timeline Result Container -->
 <div id="orderTimelineResult" class="d-none p-3 rounded-4 bg-secondary bg-opacity-10 border border-secondary">
 <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-2">
 <div>
 <span class="fw-bold text-warning small" id="resOrderId">Order #98241</span>
 <div class="small text-white-50" id="resOrderCustomer">Tanvir Ahmed (Dhaka)</div>
 </div>
 <span class="badge bg-success rounded-pill px-3 py-2 fw-bold" id="resOrderStatus">Out for Delivery</span>
 </div>

 <!-- Timeline Visual Progress -->
 <div class="deen-order-timeline my-3">
 <div class="deen-timeline-step completed">
 <div class="deen-timeline-dot"><i class="fas fa-check"></i></div>
 <div class="fw-bold small text-white">Order Confirmed</div>
 <div class="small text-white-50">Aug 14, 2026 • Payment Verified</div>
 </div>
 <div class="deen-timeline-step completed">
 <div class="deen-timeline-dot"><i class="fas fa-check"></i></div>
 <div class="fw-bold small text-white">Packed at Dhaka Hub</div>
 <div class="small text-white-50">Aug 14, 2026 • Standard Inspection</div>
 </div>
 <div class="deen-timeline-step active">
 <div class="deen-timeline-dot"><i class="fas fa-truck"></i></div>
 <div class="fw-bold small text-warning">Handed to Steadfast Courier</div>
 <div class="small text-white-50">Tracking: ST-882194 (Est. Today 5 PM)</div>
 </div>
 <div class="deen-timeline-step">
 <div class="deen-timeline-dot"><i class="fas fa-home"></i></div>
 <div class="fw-bold small text-white-50">Delivered to Doorstep</div>
 <div class="small text-white-50">Pending Confirmation</div>
 </div>
 </div>
 </div>
 </div>
 </div>
 <!-- DEEN VIP LOYALTY & REWARDS MODAL -->
 <div class="modal fade" id="loyaltyModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-md">
 <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
 <div class="modal-header border-secondary border-0 pb-0">
 <h5 class="modal-title fw-bold text-warning"><i class="fas fa-crown me-2"></i> DEEN VIP Club & Rewards</h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4">
 <!-- VIP Card Showcase -->
 <div class="deen-vip-card mb-4 position-relative overflow-hidden">
 <div class="d-flex align-items-center justify-content-between mb-3">
 <div>
 <span class="badge bg-warning text-dark font-monospace fw-bold uppercase">Gold VIP Member</span>
 <h4 class="fw-bold text-white mb-0 mt-1">Deen Fashion Rewards</h4>
 </div>
 <i class="fas fa-gem fa-2x text-warning opacity-75"></i>
 </div>
 <div class="d-flex align-items-baseline gap-2">
 <span class="display-5 fw-bold text-warning" id="userCoinsDisplay">450</span>
 <span class="text-white-50 fw-semibold">Deen Coins Available</span>
 </div>
 <p class="small text-white-50 mb-0 mt-2"><i class="fas fa-circle-info me-1"></i> Earn 1 Deen Coin for every ৳10 spent on fashion orders!</p>
 </div>

 <!-- Available Vouchers & Rewards Catalogue -->
 <h6 class="text-uppercase text-white-50 small fw-bold mb-3">Redeem Your Reward Coins</h6>
 <div class="d-flex flex-column gap-3">
 <div class="p-3 rounded-3 bg-secondary bg-opacity-20 border border-secondary d-flex align-items-center justify-content-between">
 <div class="d-flex align-items-center gap-3">
 <div class="bg-warning text-dark p-2 rounded-3 fw-bold"><i class="fas fa-ticket-simple fa-lg"></i></div>
 <div>
 <div class="fw-bold text-white small">৳100 OFF Flat Discount Coupon</div>
 <div class="small text-warning">Requires 100 Coins</div>
 </div>
 </div>
 <button class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-3" onclick="alert('🎉 Voucher Claimed! Use code DEEN100 at checkout for ৳100 OFF.')">Claim</button>
 </div>

 <div class="p-3 rounded-3 bg-secondary bg-opacity-20 border border-secondary d-flex align-items-center justify-content-between">
 <div class="d-flex align-items-center gap-3">
 <div class="bg-success text-white p-2 rounded-3 fw-bold"><i class="fas fa-truck-fast fa-lg"></i></div>
 <div>
 <div class="fw-bold text-white small">Free Shipping Pass (Anywhere BD)</div>
 <div class="small text-success">Requires 200 Coins</div>
 </div>
 </div>
 <button class="btn btn-sm btn-success rounded-pill fw-bold text-white px-3" onclick="alert('🎉 Free Shipping Voucher Claimed! Use code FREESHIP at checkout.')">Claim</button>
 </div>

 <div class="p-3 rounded-3 bg-secondary bg-opacity-20 border border-secondary d-flex align-items-center justify-content-between opacity-75">
 <div class="d-flex align-items-center gap-3">
 <div class="bg-danger text-white p-2 rounded-3 fw-bold"><i class="fas fa-crown fa-lg"></i></div>
 <div>
 <div class="fw-bold text-white small">15% OFF VIP Fashion Pass</div>
 <div class="small text-white-50">Requires 500 Coins (Need 50 more)</div>
 </div>
 </div>
 <button class="btn btn-sm btn-outline-light rounded-pill fw-bold px-3" disabled>Locked</button>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- MOBILE ACCOUNT NAVIGATION DRAWER / MODAL -->
 <div class="modal fade" id="mobileAccountModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-sm">
 <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden bg-white">
 <div class="modal-header bg-dark text-white border-0 py-3">
 <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2 mb-0">
 <span class="material-symbols-outlined text-warning fs-5">account_circle</span>
 <span>Client Account & Services</span>
 </h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-3">
 @auth
 <div class="p-3 deen-frame deen-pastel-azure mb-3 rounded-3">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white fw-bold shadow-2xs">
 {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
 </div>
 <div class="overflow-hidden">
 <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
 <div class="small text-secondary text-truncate" style="max-width: 170px;">{{ Auth::user()->email }}</div>
 </div>
 </div>
 </div>
 @else
 <div class="p-3 deen-frame deen-pastel-linen mb-3 rounded-3">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <div class="d-flex align-items-center gap-2">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark text-white">
 <span class="material-symbols-outlined fs-5">person</span>
 </div>
 <div>
 <div class="fw-bold text-dark small">Client Membership</div>
 <div class="text-secondary">Sign in or create account</div>
 </div>
 </div>
 <button type="button" onclick="closeMobileAccountModal(); openAuthModal('login');" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-none" style="font-size: 0.78rem;">
 <span class="material-symbols-outlined fs-6">login</span>
 <span>Sign In</span>
 </button>
 </div>
 <!-- Quick 1-Tap OAuth in Mobile Drawer -->
 <div class="row g-2 pt-2 border-top">
 <div class="col-6">
 <a href="{{ route('auth.google') }}" class="btn deen-social-btn-google w-100 py-1.5 px-2 text-truncate">
 <svg width="14" height="14" viewBox="0 0 48 48">
 <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
 <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
 <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.28-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
 <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
 </svg>
 <span>Google</span>
 </a>
 </div>
 <div class="col-6">
 <a href="{{ route('auth.facebook') }}" class="btn deen-social-btn-fb w-100 py-1.5 px-2 text-truncate">
 <i class="fab fa-facebook-f"></i>
 <span>Facebook</span>
 </a>
 </div>
 </div>
 </div>
 @endauth

 <div class="d-flex flex-column gap-2">
 <!-- Track Order -->
 <a href="#" onclick="event.preventDefault(); closeMobileAccountModal(); openOrderTrackModal();" class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white text-decoration-none text-dark shadow-sm">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success">
 <span class="material-symbols-outlined fs-5">local_shipping</span>
 </div>
 <div>
 <div class="fw-semibold small">Track Live Order</div>
 <div class="text-secondary">Real-time courier dispatch status</div>
 </div>
 </div>
 <span class="material-symbols-outlined text-secondary fs-5">chevron_right</span>
 </a>

 <!-- Saved Wishlist -->
 <a href="#" onclick="event.preventDefault(); closeMobileAccountModal(); openWishlistModal();" class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white text-decoration-none text-dark shadow-sm">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger">
 <span class="material-symbols-outlined fs-5">favorite</span>
 </div>
 <div>
 <div class="fw-semibold small">Saved Wishlist</div>
 <div class="text-secondary">Favorite denim & apparel picks</div>
 </div>
 </div>
 <span class="badge bg-danger rounded-pill" id="mobileAccountWishlistBadge">0</span>
 </a>

 <!-- VIP Rewards Vault -->
 <a href="#" onclick="event.preventDefault(); closeMobileAccountModal(); openLoyaltyModal();" class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white text-decoration-none text-dark shadow-sm">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning">
 <span class="material-symbols-outlined fs-5">military_tech</span>
 </div>
 <div>
 <div class="fw-semibold small">VIP Rewards Vault</div>
 <div class="text-secondary">Coins balance & discount coupons</div>
 </div>
 </div>
 <span class="deen-pastel-pill sand py-0.5 px-2">450 Coins</span>
 </a>

 @auth
 <!-- Customer Profile & Orders -->
 <a href="{{ route('account.dashboard') }}" class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white text-decoration-none text-dark shadow-sm">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
 <span class="material-symbols-outlined fs-5">manage_accounts</span>
 </div>
 <div>
 <div class="fw-semibold small">My Profile & Addresses</div>
 <div class="text-secondary">Manage delivery details</div>
 </div>
 </div>
 <span class="material-symbols-outlined text-secondary fs-5">chevron_right</span>
 </a>

 <!-- Logout -->
 <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border border-danger-subtle bg-danger bg-opacity-10 text-decoration-none text-danger shadow-sm">
 <div class="d-flex align-items-center gap-2.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
 <span class="material-symbols-outlined fs-5">logout</span>
 </div>
 <div class="fw-semibold small">Sign Out</div>
 </div>
 <span class="material-symbols-outlined fs-5">chevron_right</span>
 </a>
 @endauth
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- UNIFIED SIGN IN & SIGN UP MODAL -->
 <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-md">
 <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden bg-white">
 <div class="modal-header bg-dark text-white border-0 py-3">
 <div class="d-flex align-items-center gap-2">
 <span class="material-symbols-outlined text-warning fs-5">lock_person</span>
 <h5 class="modal-title fw-bold fs-6 mb-0" id="modalAuthTitle">Welcome Back to Deen</h5>
 </div>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body p-4">
 <!-- VIP Micro-Banner -->
 <div class="deen-auth-perks-box mb-3">
 <div class="d-flex align-items-center justify-content-between gap-1 text-center text-md-start flex-wrap">
 <div class="d-flex align-items-center gap-1">
 <span class="material-symbols-outlined fs-6 text-warning">monetization_on</span>
 <span class="small fw-bold text-dark">50 Free Coins</span>
 </div>
 <div class="d-flex align-items-center gap-1">
 <span class="material-symbols-outlined fs-6 text-primary">local_shipping</span>
 <span class="small fw-bold text-dark">Free Delivery Pass</span>
 </div>
 <div class="d-flex align-items-center gap-1">
 <span class="material-symbols-outlined fs-6 text-success">verified</span>
 <span class="small fw-bold text-dark">7-Day Guarantee</span>
 </div>
 </div>
 </div>

 <!-- 1-Tap Social OAuth Grid: Google & Facebook -->
 <div class="row g-2 mb-3">
 <div class="col-6">
 <a href="{{ route('auth.google') }}" class="btn deen-social-btn-google w-100 shadow-sm" title="Sign in with Google">
 <svg width="16" height="16" viewBox="0 0 48 48">
 <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
 <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
 <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.28-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
 <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
 </svg>
 <span>Google</span>
 </a>
 </div>
 <div class="col-6">
 <a href="{{ route('auth.facebook') }}" class="btn deen-social-btn-fb w-100 shadow-sm" title="Sign in with Facebook">
 <i class="fab fa-facebook-f fs-6"></i>
 <span>Facebook</span>
 </a>
 </div>
 </div>

 <div class="d-flex align-items-center my-3">
 <hr class="flex-grow-1 text-muted opacity-25 m-0">
 <span class="px-2 text-muted small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.08em;">or email</span>
 <hr class="flex-grow-1 text-muted opacity-25 m-0">
 </div>

 <!-- Interactive Tab Switcher -->
 <div class="bg-light p-1 rounded-pill d-flex mb-3 border">
 <button type="button" id="modalTabLoginBtn" onclick="switchModalAuthTab('login')" class="btn btn-sm w-50 rounded-pill fw-bold py-1.5 btn-dark shadow-sm">
 Sign In
 </button>
 <button type="button" id="modalTabRegisterBtn" onclick="switchModalAuthTab('register')" class="btn btn-sm w-50 rounded-pill fw-bold py-1.5 text-muted">
 Create Account
 </button>
 </div>

 <!-- Sign In Form -->
 <div id="modalLoginFormContainer">
 <form method="POST" action="{{ route('login') }}">
 @csrf
 <div class="mb-2.5">
 <label class="form-label fw-semibold text-dark small mb-1">Email Address</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
 <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="name@example.com" required autocomplete="email">
 </div>
 </div>
 <div class="mb-2.5">
 <div class="d-flex justify-content-between align-items-center mb-1">
 <label class="form-label fw-semibold text-dark small mb-0">Password</label>
 @if (Route::has('password.request'))
 <a class="small text-primary text-decoration-none fw-semibold" href="{{ route('password.request') }}">Forgot?</a>
 @endif
 </div>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
 <input type="password" id="modalLoginPassword" name="password" class="form-control bg-light border-start-0 border-end-0" placeholder="••••••••" required autocomplete="current-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('modalLoginPassword', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>
 <div class="mb-3 form-check">
 <input class="form-check-input" type="checkbox" name="remember" id="modalRemember" checked>
 <label class="form-check-label small text-muted" for="modalRemember">Remember me</label>
 </div>
 <button type="submit" class="btn-deen-primary w-100 justify-content-center py-2 fs-6 shadow-sm">
 Sign In <i class="fas fa-arrow-right ms-1"></i>
 </button>
 </form>
 </div>

 <!-- Create Account Form -->
 <div id="modalRegisterFormContainer" class="d-none">
 <form method="POST" action="{{ route('register') }}">
 @csrf
 <div class="mb-2">
 <label class="form-label fw-semibold text-dark small mb-1">Full Name</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-user"></i></span>
 <input type="text" name="name" class="form-control bg-light border-start-0" placeholder="e.g. Tanvir Ahmed" required autocomplete="name">
 </div>
 </div>
 <div class="mb-2">
 <label class="form-label fw-semibold text-dark small mb-1">Email Address</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
 <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="name@example.com" required autocomplete="email">
 </div>
 </div>
 <div class="mb-2">
 <label class="form-label fw-semibold text-dark small mb-1">Password</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
 <input type="password" id="modalRegPassword" name="password" class="form-control bg-light border-start-0 border-end-0" placeholder="Min. 8 chars" required minlength="8" autocomplete="new-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('modalRegPassword', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>
 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small mb-1">Confirm Password</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-shield-alt"></i></span>
 <input type="password" id="modalRegConfirm" name="password_confirmation" class="form-control bg-light border-start-0 border-end-0" placeholder="Re-enter password" required minlength="8" autocomplete="new-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('modalRegConfirm', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>
 <button type="submit" class="btn-deen-orange w-100 justify-content-center py-2 fs-6 shadow-sm">
 Create VIP Account <i class="fas fa-arrow-right ms-1"></i>
 </button>
 </form>
 </div>

 <!-- Trust Indicators -->
 <div class="text-center mt-3 pt-2 border-top">
 <div class="d-inline-flex align-items-center gap-2 text-muted" style="font-size: 0.68rem;">
 <span><i class="fas fa-lock me-1"></i> 256-Bit SSL</span>
 <span>&bull;</span>
 <span><i class="fas fa-shield-halved me-1"></i> 100% Spam Free</span>
 </div>
 </div>
 </div>
 </div>
 </div>
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
 denim: '13.5oz Denim',
 dark: 'Midnight Dark',
 glass: 'Crystal Glass',
 neon: 'Cyberpunk Neon',
 light: 'Studio Light'
 };
 const el = document.getElementById('currentThemeName');
 if (el) el.innerText = names[themeName] || '13.5oz Denim';
 }

 /* GLOBAL CART PERSISTENCE & SYNC */
 function getStoredCart() {
 try {
 return JSON.parse(localStorage.getItem('deen_cart') || '[]');
 } catch (e) {
 return [];
 }
 }

 function syncCartBadges() {
 const cart = getStoredCart();
 const totalCount = cart.reduce((acc, item) => acc + (item.qty || 1), 0);
 
 const b1 = document.getElementById('bottomNavCartBadge');
 if (b1) b1.innerText = totalCount;

 const b2 = document.getElementById('headerMobileCartBadge');
 if (b2) b2.innerText = totalCount;

 const b3 = document.getElementById('cartCount');
 if (b3) b3.innerText = totalCount;
 }

 function openGlobalCartModal() {
 renderGlobalCartList();
 const modal = new bootstrap.Modal(document.getElementById('globalCartModal'));
 modal.show();
 }

 function renderGlobalCartList() {
 const cart = getStoredCart();
 const listContainer = document.getElementById('globalCartItemsList');
 const totalContainer = document.getElementById('globalCartTotal');

 if (cart.length === 0) {
 listContainer.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-shopping-cart fa-3x mb-3 opacity-40"></i><p class="mb-0">Your shopping cart is currently empty.</p></div>';
 totalContainer.innerText = '৳0.00';
 return;
 }

 let total = 0;
 let html = '<div class="d-flex flex-column gap-3">';
 cart.forEach((item, idx) => {
 const sub = (item.price || 0) * (item.qty || 1);
 total += sub;
 const img = item.img || item.image || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
 html += `
 <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
 <div class="d-flex align-items-center gap-2">
 <img src="${img}" loading="lazy" alt="Cart item" class="deen-avatar-2xl deen-avatar-cover rounded-2 border">
 <div>
 <div class="fw-bold small text-dark text-truncate" class="deen-max-160">${item.name}</div>
 <div class="small text-muted">৳${item.price.toFixed(2)} x ${item.qty}</div>
 </div>
 </div>
 <div class="d-flex align-items-center gap-2">
 <div class="fw-bold text-dark">৳${sub.toFixed(2)}</div>
 <button class="btn btn-sm text-danger p-0 ms-1" onclick="removeGlobalCartItem(${idx})"><i class="fas fa-trash-alt"></i></button>
 </div>
 </div>
 `;
 });
 html += '</div>';

 let appliedCoupon = localStorage.getItem('deen_applied_coupon');
 let discountAmount = 0;
 let discountLabel = '';

 if (appliedCoupon === 'DEEN2026') {
 discountAmount = total * 0.20;
 discountLabel = '20% OFF (DEEN2026)';
 } else if (appliedCoupon === 'DEEN10') {
 discountAmount = 100;
 discountLabel = '৳100 OFF (DEEN10)';
 }

 if (discountAmount > 0) {
 let finalTotal = Math.max(0, total - discountAmount);
 totalContainer.innerHTML = `<span class="text-decoration-line-through text-muted fs-6 me-2">৳${total.toFixed(2)}</span> <span class="text-danger fw-bold">৳${finalTotal.toFixed(2)}</span> <div class="text-success small fw-bold mt-1"><i class="fas fa-tag me-1"></i> Coupon: ${discountLabel}</div>`;
 } else {
 totalContainer.innerText = '৳' + total.toFixed(2);
 }

 listContainer.innerHTML = html;
 }

 function applyCartCoupon() {
 const input = document.getElementById('cartCouponInput');
 const msg = document.getElementById('cartCouponMessage');
 if (!input || !msg) return;

 const code = input.value.trim().toUpperCase();
 if (code === 'DEEN2026' || code === 'DEEN10') {
 localStorage.setItem('deen_applied_coupon', code);
 msg.className = 'small mt-1 text-center font-monospace text-success fw-bold';
 msg.innerText = '✓ Coupon ' + code + ' Applied Successfully!';
 renderGlobalCartList();
 } else {
 msg.className = 'small mt-1 text-center font-monospace text-danger fw-bold';
 msg.innerText = '✕ Invalid Coupon Code. Try DEEN2026 or DEEN10.';
 }
 }

 function removeGlobalCartItem(idx) {
 let cart = getStoredCart();
 cart.splice(idx, 1);
 localStorage.setItem('deen_cart', JSON.stringify(cart));
 syncCartBadges();
 renderGlobalCartList();
 }

 /* PREDICTIVE LIVE SEARCH LOGIC */
 let searchDebounceTimer;

 function openMobileSearchModal() {
 const modal = new bootstrap.Modal(document.getElementById('mobileSearchModal'));
 modal.show();
 setTimeout(() => {
 document.getElementById('predictiveSearchInput').focus();
 }, 400);
 }

 function setPredictiveSearch(val) {
 const input = document.getElementById('predictiveSearchInput');
 input.value = val;
 handlePredictiveSearch(val);
 }

 function clearPredictiveSearch() {
 const input = document.getElementById('predictiveSearchInput');
 input.value = '';
 document.getElementById('predictiveResultsList').innerHTML = `
 <div class="text-center py-4 text-white-50">
 <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
 <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
 </div>
 `;
 }

 function handlePredictiveSearch(query) {
 clearTimeout(searchDebounceTimer);
 const container = document.getElementById('predictiveResultsList');

 if (!query || query.trim().length < 2) {
 container.innerHTML = `
 <div class="text-center py-4 text-white-50">
 <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
 <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
 </div>
 `;
 return;
 }

 container.innerHTML = `
 <div class="text-center py-4 text-white-50">
 <i class="fas fa-spinner fa-spin fa-2x mb-2 text-warning"></i>
 <p class="mb-0 small">Searching Deen Commerce catalog...</p>
 </div>
 `;

 searchDebounceTimer = setTimeout(() => {
 fetch('/store/search/suggestions?q=' + encodeURIComponent(query.trim()))
 .then(res => res.json())
 .then(data => {
 if (data.success && data.suggestions && data.suggestions.length > 0) {
 let html = '<div class="d-flex flex-column gap-2">';
 data.suggestions.forEach(item => {
 const img = item.image || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
 const regPrice = item.regular_price ? `<span class="small text-white-50 text-decoration-line-through me-1">৳${item.regular_price}</span>` : '';
 html += `
 <a href="${item.detail_url}" class="deen-search-item rounded-3">
 <img src="${img}" loading="lazy" alt="${item.name}" class="deen-search-thumb">
 <div class="deen-search-info">
 <div class="deen-search-name text-white">${item.name}</div>
 <div class="deen-search-meta">
 <span class="deen-search-price">৳${item.price.toFixed(2)}</span>
 ${regPrice}
 <span class="badge bg-success rounded-pill px-2">In Stock</span>
 </div>
 </div>
 <span class="material-symbols-outlined text-warning fs-5">arrow_forward_ios</span>
 </a>
 `;
 });
 html += `
 <a href="/?search=${encodeURIComponent(query.trim())}" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold mt-2 py-2 text-dark">
 View all matching results for "${query}" &rarr;
 </a>
 </div>`;
 container.innerHTML = html;
 } else {
 container.innerHTML = `
 <div class="text-center py-4 text-white-50">
 <span class="material-symbols-outlined fs-1 text-danger mb-2">search_off</span>
 <p class="mb-1 fw-bold text-white">No items found for "${query}"</p>
 <p class="small mb-0">Try searching for "jeans", "shirts", or "polos"</p>
 </div>
 `;
 }
 })
 .catch(() => {
 container.innerHTML = '<div class="alert alert-danger py-2 small">Error fetching search results.</div>';
 });
 }, 300);
 }

 /* TELEGRAM CHATBOT POPOVER TOGGLE */
 function toggleTelegramChatPopover() {
 const popover = document.getElementById('telegramChatPopover');
 if (popover) {
 popover.classList.toggle('show');
 }
 }

 document.addEventListener('click', (e) => {
 const widget = e.target.closest('.deen-telegram-widget-wrapper');
 const popover = document.getElementById('telegramChatPopover');
 if (!widget && popover && popover.classList.contains('show')) {
 popover.classList.remove('show');
 }
 });

 /* WISHLIST LOCALSTORAGE & UI SYNC */
 function getWishlist() {
 try {
 return JSON.parse(localStorage.getItem('deen_wishlist') || '[]');
 } catch (e) {
 return [];
 }
 }

 function toggleWishlist(id, name, price, img, btnEl = null) {
 let wishlist = getWishlist();
 const index = wishlist.findIndex(item => item.id === id);

 if (index > -1) {
 wishlist.splice(index, 1);
 if (btnEl) btnEl.classList.remove('active');
 } else {
 wishlist.push({ id, name, price, img });
 if (btnEl) btnEl.classList.add('active');
 }

 localStorage.setItem('deen_wishlist', JSON.stringify(wishlist));
 syncWishlistUI();
 }

 function syncWishlistUI() {
 const wishlist = getWishlist();
 const count = wishlist.length;

 const badge = document.getElementById('bottomNavWishlistBadge');
 if (badge) badge.innerText = count;

 const badge2 = document.getElementById('mobileAccountWishlistBadge');
 if (badge2) badge2.innerText = count;

 const badge3 = document.getElementById('navWishlistCount');
 if (badge3) badge3.innerText = count;

 const badge4 = document.getElementById('headerMobileWishlistBadge');
 if (badge4) badge4.innerText = count;

 // Highlight heart buttons on page matching wishlist IDs
 document.querySelectorAll('.deen-wishlist-btn').forEach(btn => {
 const btnId = parseInt(btn.getAttribute('data-id'));
 if (wishlist.some(w => w.id === btnId)) {
 btn.classList.add('active');
 } else {
 btn.classList.remove('active');
 }
 });
 }

 function openWishlistModal() {
 renderWishlistList();
 const modal = new bootstrap.Modal(document.getElementById('wishlistModal'));
 modal.show();
 }

 function renderWishlistList() {
 const wishlist = getWishlist();
 const container = document.getElementById('wishlistItemsList');

 if (wishlist.length === 0) {
 container.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-heart fa-3x mb-3 opacity-40 text-danger"></i><p class="mb-0">Your wishlist is empty. Click the heart icon on any product to save it here!</p></div>';
 return;
 }

 let html = '<div class="d-flex flex-column gap-3">';
 wishlist.forEach((item, idx) => {
 const img = item.img || item.image || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
 html += `
 <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
 <div class="d-flex align-items-center gap-2">
 <img src="${img}" loading="lazy" alt="Wishlist item" class="deen-avatar-3xl deen-avatar-cover rounded-2 border">
 <div>
 <div class="fw-bold small text-dark text-truncate" class="deen-max-170">${item.name}</div>
 <div class="fw-bold text-primary small">৳${parseFloat(item.price).toFixed(2)}</div>
 </div>
 </div>
 <div class="d-flex align-items-center gap-2">
 <button class="btn btn-sm btn-dark rounded-pill fw-bold" onclick="addToCart(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, '${img}')">
 <i class="fas fa-shopping-cart me-1"></i> Add
 </button>
 <button class="btn btn-sm text-danger p-0 ms-1" onclick="toggleWishlist(${item.id}, '', 0, ''); renderWishlistList();"><i class="fas fa-trash-alt"></i></button>
 </div>
 </div>
 `;
 });
 html += '</div>';
 container.innerHTML = html;
 }

 /* ORDER TRACKING MODAL LOGIC */
 function openOrderTrackModal() {
 const modal = new bootstrap.Modal(document.getElementById('orderTrackModal'));
 modal.show();
 }

 function trackOrderInline() {
 const inputVal = (document.getElementById('trackInput').value || '').trim();
 const resBox = document.getElementById('orderTimelineResult');

 if (!inputVal) return;

 resBox.classList.remove('d-none');
 document.getElementById('resOrderId').innerText = 'Order #' + (inputVal.replace('#', '') || '98241');
 document.getElementById('resOrderCustomer').innerText = 'Deen Verified Customer (Dhaka Hub)';
 document.getElementById('resOrderStatus').innerText = 'Out for Delivery';
 }

 /* LOYALTY & REWARDS MODAL LOGIC */
 function openLoyaltyModal() {
 const modal = new bootstrap.Modal(document.getElementById('loyaltyModal'));
 modal.show();
 }

 /* RECENTLY VIEWED PRODUCTS LOGIC */
 function trackRecentlyViewed(id, name, price, img) {
 if (!id || !name) return;
 let recent = getRecentlyViewed();
 recent = recent.filter(item => item.id !== id);
 recent.unshift({ id, name, price, img });
 if (recent.length > 8) recent.pop();
 localStorage.setItem('deen_recently_viewed', JSON.stringify(recent));
 }

 function getRecentlyViewed() {
 try {
 return JSON.parse(localStorage.getItem('deen_recently_viewed') || '[]');
 } catch (e) {
 return [];
 }
 }

 function renderRecentlyViewed() {
 const container = document.getElementById('recentlyViewedContainer');
 const section = document.getElementById('recentlyViewedSection');
 if (!container) return;

 const recent = getRecentlyViewed();
 if (recent.length === 0) {
 if (section) section.classList.add('d-none');
 return;
 }

 if (section) section.classList.remove('d-none');

 let html = '';
 recent.forEach(item => {
 const img = item.img || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
 html += `
 <a href="/store/product/${item.id}" onclick="trackRecentlyViewed(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, '${img}')" class="deen-recently-card shadow-sm">
 <img src="${img}" loading="lazy" alt="${item.name}" class="deen-recently-img">
 <div class="fw-bold small text-white text-truncate" title="${item.name}">${item.name}</div>
 <div class="fw-bold text-warning small">৳${parseFloat(item.price).toFixed(2)}</div>
 </a>
 `;
 });
 container.innerHTML = html;
 }

 function showCartToast(itemTitle) {
 const toast = document.getElementById('globalCartToast');
 const titleEl = document.getElementById('toastItemTitle');
 if (toast && titleEl) {
 titleEl.innerText = itemTitle + ' added to cart!';
 toast.classList.remove('d-none');
 setTimeout(() => {
 toast.classList.add('d-none');
 }, 4500);
 }
 }

 /* MOBILE ACCOUNT DRAWER & AUTH MODAL HANDLERS */
 function openMobileAccountModal() {
 const modal = new bootstrap.Modal(document.getElementById('mobileAccountModal'));
 modal.show();
 }

 function closeMobileAccountModal() {
 const modalEl = document.getElementById('mobileAccountModal');
 const modal = bootstrap.Modal.getInstance(modalEl);
 if (modal) modal.hide();
 }

 function openAuthModal(tab = 'login') {
 switchModalAuthTab(tab);
 const modal = new bootstrap.Modal(document.getElementById('authModal'));
 modal.show();
 }

 function switchModalAuthTab(tab) {
 const loginContainer = document.getElementById('modalLoginFormContainer');
 const registerContainer = document.getElementById('modalRegisterFormContainer');
 const loginBtn = document.getElementById('modalTabLoginBtn');
 const registerBtn = document.getElementById('modalTabRegisterBtn');
 const titleEl = document.getElementById('modalAuthTitle');

 if (tab === 'login') {
 if (loginContainer) loginContainer.classList.remove('d-none');
 if (registerContainer) registerContainer.classList.add('d-none');
 if (loginBtn) loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-1.5 btn-dark shadow-sm';
 if (registerBtn) registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-1.5 text-muted';
 if (titleEl) titleEl.innerText = 'Welcome Back to Deen';
 } else {
 if (loginContainer) loginContainer.classList.add('d-none');
 if (registerContainer) registerContainer.classList.remove('d-none');
 if (registerBtn) registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-1.5 btn-dark shadow-sm';
 if (loginBtn) loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-1.5 text-muted';
 if (titleEl) titleEl.innerText = 'Join the Deen VIP Club';
 }
 }

 function togglePassword(inputId, btn) {
 const input = document.getElementById(inputId);
 if (!input) return;
 const icon = btn.querySelector('i');
 if (input.type === 'password') {
 input.type = 'text';
 if (icon) icon.className = 'fas fa-eye-slash';
 } else {
 input.type = 'password';
 if (icon) icon.className = 'fas fa-eye';
 }
 }

 /* Flash Sale Countdown Timer (global — works on all pages) */
 function startFlashSaleTimer() {
 let totalSeconds = 5 * 3600 + 42 * 60 + 18;
 const timerEl = document.getElementById('flashSaleTimer');
 if (!timerEl) return;
 setInterval(() => {
 if (totalSeconds <= 0) totalSeconds = 24 * 3600;
 totalSeconds--;
 const hrs = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
 const mins = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
 const secs = String(totalSeconds % 60).padStart(2, '0');
 timerEl.innerText = `ENDS IN ${hrs}h : ${mins}m : ${secs}s`;
 }, 1000);
 }

 document.addEventListener('DOMContentLoaded', () => {
 const saved = localStorage.getItem('deen_theme') || 'denim';
 updateThemeLabel(saved);
 syncCartBadges();
 syncWishlistUI();
 renderRecentlyViewed();
 startFlashSaleTimer();

 // Global Keyboard Shortcut (Ctrl+K or Cmd+K to open Search)
 document.addEventListener('keydown', (e) => {
 if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
 e.preventDefault();
 openMobileSearchModal();
 }
 });

 // Register Progressive Web App Service Worker
 if ('serviceWorker' in navigator) {
 navigator.serviceWorker.register('/sw.js')
 .then(reg => console.log('Deen PWA ServiceWorker Registered:', reg.scope))
 .catch(err => console.log('ServiceWorker registration failed:', err));
 }
 });
 </script>
</body>
</html>