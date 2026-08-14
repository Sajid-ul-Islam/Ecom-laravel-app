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

                <!-- Mobile Header Direct Action Buttons (Search & Cart & Toggle) -->
                <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
                    <button class="btn btn-outline-warning btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" onclick="openMobileSearchModal()" title="Instant Search">
                        <span class="material-symbols-outlined fs-5">search</span>
                    </button>
                    <button class="btn btn-warning btn-sm rounded-circle p-2 position-relative d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" onclick="openGlobalCartModal()" title="Cart">
                        <span class="material-symbols-outlined fs-5 text-dark">shopping_bag</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="headerMobileCartBadge">0</span>
                    </button>
                </div>

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

        <!-- STICKY MOBILE BOTTOM THUMB-ZONE NAVIGATION BAR -->
        <div class="deen-mobile-bottom-nav">
            <a href="{{ route('store.index') }}" class="deen-mobile-nav-item {{ request()->is('/') ? 'active' : '' }}">
                <span class="material-symbols-outlined nav-icon">storefront</span>
                <span>Shop</span>
            </a>
            <a href="{{ route('store.categories') }}" class="deen-mobile-nav-item {{ request()->is('categories*') || request()->is('category*') ? 'active' : '' }}">
                <span class="material-symbols-outlined nav-icon">grid_view</span>
                <span>Categories</span>
            </a>
            <a href="#" onclick="event.preventDefault(); openMobileSearchModal();" class="deen-mobile-nav-item">
                <span class="material-symbols-outlined nav-icon">search</span>
                <span>Search</span>
            </a>
            <a href="#" onclick="event.preventDefault(); openGlobalCartModal();" class="deen-mobile-nav-item">
                <span class="material-symbols-outlined nav-icon">shopping_bag</span>
                <span>Bag</span>
                <span class="deen-mobile-nav-badge" id="bottomNavCartBadge">0</span>
            </a>
            <a href="{{ route('account.dashboard') }}" class="deen-mobile-nav-item {{ request()->is('my-account*') ? 'active' : '' }}">
                <span class="material-symbols-outlined nav-icon">person</span>
                <span>Account</span>
        </div>

        <!-- TELEGRAM CUSTOMER CARE CHATBOT WIDGET (@DEEN_Commerce_bot) -->
        <div class="deen-telegram-widget-wrapper">
            <!-- Popover Card -->
            <div class="deen-telegram-popover" id="telegramChatPopover">
                <div class="deen-telegram-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fab fa-telegram fa-lg text-white"></i>
                        <div>
                            <div class="fw-bold small">DEEN Commerce Assistant</div>
                            <div class="small opacity-75" style="font-size: 0.72rem;"><i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> @DEEN_Commerce_bot</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white btn-sm" onclick="toggleTelegramChatPopover()"></button>
                </div>
                <div class="deen-telegram-body">
                    <div class="deen-telegram-chat-bubble">
                        <div class="fw-bold mb-1 text-warning"><i class="fas fa-robot me-1"></i> Assalamu Alaikum!</div>
                        Need help with your denim sizes, order tracking, returns, or product stock? Chat live with our official Telegram AI Assistant!
                    </div>
                    <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="deen-telegram-btn mb-2">
                        <i class="fab fa-telegram-plane"></i> Open Telegram Bot App
                    </a>
                    <a href="https://web.telegram.org/k/#@DEEN_Commerce_bot" target="_blank" class="btn btn-sm btn-outline-light w-100 rounded-pill text-white-50" style="font-size: 0.78rem;">
                        <i class="fas fa-globe me-1"></i> Open Telegram Web Client
                    </a>
                </div>
            </div>

            <!-- Floating Trigger Button -->
            <button class="deen-telegram-trigger" onclick="toggleTelegramChatPopover()" title="Chat with @DEEN_Commerce_bot on Telegram">
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
                    <div id="predictiveResultsList">
                        <div class="text-center py-4 text-white-50">
                            <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
                            <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GLOBAL SHOPPING BAG MODAL -->
    <div class="modal fade" id="globalCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-md">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-shopping-bag me-2 text-danger"></i> Shopping Bag</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="globalCartItemsList">
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-shopping-bag fa-3x mb-3 opacity-40"></i>
                        <p class="mb-0">Your shopping bag is empty.</p>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex flex-column gap-2">
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
            listContainer.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-shopping-bag fa-3x mb-3 opacity-40"></i><p class="mb-0">Your shopping bag is empty.</p></div>';
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
                        <img src="${img}" style="width: 48px; height: 48px; object-fit: cover;" class="rounded-2 border">
                        <div>
                            <div class="fw-bold small text-dark text-truncate" style="max-width: 160px;">${item.name}</div>
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

        listContainer.innerHTML = html;
        totalContainer.innerText = '৳' + total.toFixed(2);
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
                                    <img src="${img}" class="deen-search-thumb" alt="${item.name}">
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

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('deen_theme') || 'denim';
        updateThemeLabel(saved);
        syncCartBadges();
    });
    </script>
</body>
</html>