<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deen Commerce (দীন কমার্স) - Premium Retail Fashion & Urban Apparel E-Store</title>
    <link rel="icon" href="https://deencommerce.com/wp-content/uploads/2025/04/cropped-cropped-Deen-Logo-scaled-1.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Deen Commerce Retail Custom CSS -->
    <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">
</head>


<body>
    <!-- Top Announcement Bar -->
    <div class="deen-promo-bar">
        <i class="fas fa-truck-fast me-1"></i> FREE SHIPPING ON ORDERS OVER ৳2,000 &bull; NEW SEASON DENIM & URBAN FASHION 2026
    </div>

    <!-- Deen Commerce Retail Navbar -->
    <nav class="navbar navbar-expand-lg deen-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="https://deencommerce.com/wp-content/uploads/2025/04/Deen-Logo-Light-scaled.png" alt="Deen Commerce (দীন কমার্স)" style="height: 38px; object-fit: contain;">
                <span class="deen-brand-badge ms-1">Retail Store</span>
            </a>


            <button class="navbar-toggler text-white border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#deenRetailNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="deenRetailNavbar">
                <!-- Search Bar with Category Select -->
                <form method="GET" action="{{ route('store.index') }}" class="mx-auto my-2 my-lg-0" style="max-width: 520px; width: 100%;">
                    <div class="input-group">
                        <select name="category" class="form-select bg-dark text-white-50 border-secondary rounded-start-pill text-truncate" style="max-width: 150px; font-size: 0.85rem;">
                            <option value="">All Categories</option>
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat['id'] }}" {{ ($selectedCategory == $cat['id']) ? 'selected' : '' }}>
                                        {{ $cat['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary px-3" placeholder="Search jeans, shirts, polos..." value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary rounded-end-pill px-3" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <!-- Category Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-list text-primary me-1"></i> Categories
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-secondary rounded-4 py-2" style="min-width: 240px;">
                            <li><h6 class="dropdown-header text-uppercase text-muted fw-bold">Fashion Collections</h6></li>
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center justify-content-between py-2 px-3 {{ ($selectedCategory == $cat['id']) ? 'active' : '' }}" href="{{ route('store.category', $cat['id']) }}">
                                            <span><i class="fas fa-angle-right me-2 text-primary"></i> {{ $cat['name'] }}</span>
                                            <span class="badge bg-danger rounded-pill">{{ $cat['count'] ?? 0 }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li>
                                <a class="dropdown-item text-warning fw-bold py-2 px-3" href="{{ route('store.categories') }}">
                                    <i class="fas fa-border-all me-2"></i> View All Categories &rarr;
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="#catalog-section"><i class="fas fa-tshirt me-1"></i> Shop Fashion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-semibold" href="{{ route('woocommerce.dashboard') }}"><i class="fas fa-sync-alt me-1"></i> WooCommerce Hub</a>
                    </li>

                    @guest
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light rounded-pill px-3" href="{{ route('login') }}">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary rounded-pill px-3 fw-bold" href="{{ route('register') }}">Join Store</a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary rounded-pill px-3" href="{{ url('/home') }}"><i class="fas fa-user-circle me-1"></i> My Account</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Retail Hero Carousel Cover Slideshow -->
    <section class="deen-fashion-hero p-0">
        <div id="deenHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            @if(!empty($heroSlides) && count($heroSlides) > 1)
                <div class="carousel-indicators mb-4">
                    @foreach($heroSlides as $index => $slide)
                        <button type="button" data-bs-target="#deenHeroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @if(!empty($heroSlides))
                    @foreach($heroSlides as $index => $slide)
                        @php
                            $slideImg = $slide['images'][0]['src'] ?? 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
                            $slidePrice = !empty($slide['price']) ? '৳' . number_format((float)$slide['price']) : '৳2,490';
                            $regPrice = !empty($slide['regular_price']) ? '৳' . number_format((float)$slide['regular_price']) : null;
                            $tagline = $slide['tagline'] ?? ('LIVE FEATURED ITEM #' . ($slide['id'] ?? $index+1));
                            $badge = $slide['badge'] ?? 'LIVE SYNC';
                        @endphp
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }} py-5">
                            <div class="container py-4">
                                <div class="row align-items-center g-4">
                                    <div class="col-lg-7">
                                        <div class="deen-hero-tag">
                                            <i class="fas fa-sparkles me-1"></i> {{ $tagline }}
                                        </div>
                                        <h1 class="deen-hero-heading mb-3">
                                            {{ $slide['name'] }}
                                        </h1>
                                        <p class="deen-hero-desc mb-4">
                                            {!! Str::limit(strip_tags($slide['short_description'] ?? $slide['description'] ?? 'Discover premium Bangladesh denim, casual shirts, and urban apparel live from Deen Commerce.'), 140) !!}
                                        </p>
                                        <div class="d-flex flex-wrap gap-3">
                                            <a href="{{ route('store.product.detail', $slide['id'] ?? 1) }}" class="btn btn-danger btn-lg rounded-pill px-4 fw-bold shadow">
                                                <i class="fas fa-bag-shopping me-2"></i> Shop This Item
                                            </a>
                                            <a href="#catalog-section" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                                                <i class="fas fa-tshirt me-2"></i> Browse Full Catalog
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        <div class="bg-white text-dark p-3 rounded-4 shadow-lg border text-center position-relative overflow-hidden">
                                            <span class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill fw-bold z-3">{{ $badge }}</span>
                                            <div class="rounded-3 overflow-hidden mb-3" style="height: 250px; background: #f8fafc;">
                                                <img src="{{ $slideImg }}" class="w-100 h-100 object-fit-cover" alt="{{ $slide['name'] }}">
                                            </div>
                                            <div class="mb-2">
                                                <h4 class="fw-bold text-dark mb-1 text-truncate">{{ $slide['name'] }}</h4>
                                                <p class="text-muted small mb-2">Live Deen Commerce Sync</p>
                                                <div class="fs-2 fw-bold text-primary mb-3">
                                                    {{ $slidePrice }}
                                                    @if($regPrice && $regPrice !== $slidePrice)
                                                        <small class="fs-6 text-muted text-decoration-line-through">{{ $regPrice }}</small>
                                                    @endif
                                                </div>
                                                <a href="{{ route('store.product.detail', $slide['id'] ?? 1) }}" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold">
                                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if(!empty($heroSlides) && count($heroSlides) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#deenHeroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#deenHeroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </section>


    <!-- Retail Perks Value Bar -->
    <div class="container">
        <div class="deen-perks-box">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><i class="fas fa-truck-fast"></i></div>
                        <div>
                            <div class="deen-perk-title">Free Fast Shipping</div>
                            <div class="deen-perk-desc">On orders over ৳2,000</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><i class="fas fa-shield-check"></i></div>
                        <div>
                            <div class="deen-perk-title">100% Authentic</div>
                            <div class="deen-perk-desc">Original Deen Quality</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><i class="fas fa-rotate-left"></i></div>
                        <div>
                            <div class="deen-perk-title">7 Days Returns</div>
                            <div class="deen-perk-desc">Hassle-free Exchange</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><i class="fas fa-headset"></i></div>
                        <div>
                            <div class="deen-perk-title">24/7 Support</div>
                            <div class="deen-perk-desc">Dedicated Care Team</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Fashion Storefront -->
    <main class="py-5" id="catalog-section">
        <div class="container">

            <!-- Category Filter Bar -->
            @if(!empty($categories))
                <div class="d-flex align-items-center gap-2 overflow-auto pb-3 mb-4 no-scrollbar">
                    <a href="{{ route('store.index') }}" class="deen-fashion-chip {{ empty($selectedCategory) ? 'active' : '' }}">
                        <i class="fas fa-border-all"></i> All Fashion
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('store.index', ['category' => $cat['id']]) }}" class="deen-fashion-chip {{ ($selectedCategory == $cat['id']) ? 'active' : '' }}">
                            {{ $cat['name'] }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Section Header -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-tshirt text-primary me-2"></i> Featured Apparel & Denim</h3>
                    <p class="text-muted small mb-0">Browse latest fashion arrivals synced live from Deen Commerce database</p>
                </div>
                <div class="small text-muted font-monospace bg-white border px-3 py-2 rounded-pill shadow-sm">
                    Showing {{ count($products) }} items
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4 mb-5">
                @forelse($products as $product)
                    @php
                        $image = $product['images'][0]['src'] ?? null;
                        $price = (float)($product['price'] ?? 0);
                        $regularPrice = isset($product['regular_price']) ? (float)$product['regular_price'] : null;
                        $stockQty = $product['stock_quantity'] ?? null;
                        $stockStatus = $product['stock_status'] ?? 'instock';

                        $discountPercent = 0;
                        if ($regularPrice && $regularPrice > $price) {
                            $discountPercent = round((($regularPrice - $price) / $regularPrice) * 100);
                        }
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="deen-retail-card">
                            <div class="deen-retail-img-box">
                                @if($image)
                                    <img src="{{ $image }}" class="deen-retail-img" alt="{{ $product['name'] }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-tshirt fa-4x text-secondary opacity-40"></i>
                                    </div>
                                @endif

                                @if($discountPercent > 0)
                                    <span class="deen-discount-ribbon">-{{ $discountPercent }}% OFF</span>
                                @endif

                                @if($stockStatus === 'instock' || ($stockQty && $stockQty > 0))
                                    <span class="deen-stock-badge bg-success text-white">In Stock</span>
                                @else
                                    <span class="deen-stock-badge bg-danger text-white">Out of Stock</span>
                                @endif
                            </div>

                            <div class="deen-retail-body">
                                <h5 class="deen-retail-title" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>

                                <div class="deen-rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-muted ms-1 small">(4.9)</span>
                                </div>

                                <div class="deen-retail-price-row">
                                    <span class="deen-retail-price">৳{{ number_format($price, 2) }}</span>
                                    @if($regularPrice && $regularPrice > $price)
                                        <span class="deen-retail-old-price">৳{{ number_format($regularPrice, 2) }}</span>
                                    @endif
                                </div>

                                <div class="mt-auto d-grid gap-2">
                                    <button onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}')" class="deen-btn-cart w-100">
                                        <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                                    </button>
                                    <button onclick="openProductModal({{ $product['id'] }})" class="btn btn-sm btn-outline-secondary rounded-pill fw-semibold">
                                        <i class="fas fa-eye me-1"></i> Quick Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="bg-white p-5 rounded-4 shadow-sm border">
                            <i class="fas fa-tshirt fa-4x text-muted opacity-50 mb-3"></i>
                            <h4>No Fashion Items Found</h4>
                            <p class="text-muted">No Deen Commerce items matched your search query.</p>
                            <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-4">View All Products</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Bar -->
            @if(($totalPages ?? 1) > 1)
                <div class="d-flex justify-content-center align-items-center gap-2 mb-5">
                    @if($currentPage > 1)
                        <a href="{{ route('store.index', ['page' => $currentPage - 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> Previous
                        </a>
                    @endif

                    <span class="fw-bold text-dark px-3">Page {{ $currentPage }} of {{ $totalPages }}</span>

                    @if($currentPage < $totalPages)
                        <a href="{{ route('store.index', ['page' => $currentPage + 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn btn-dark rounded-pill px-4 fw-bold">
                            Next <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </main>

    <!-- Floating Cart Button -->
    <a href="#" onclick="event.preventDefault(); openCartModal();" class="deen-floating-cart">
        <i class="fas fa-shopping-bag"></i>
        <span class="deen-cart-count" id="cartCount">0</span>
    </a>

    <!-- Cart Modal Drawer -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-md">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-shopping-bag me-2 text-danger"></i> Your Shopping Bag</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="cartItemsList">
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-shopping-bag fa-3x mb-3 opacity-40"></i>
                        <p class="mb-0">Your shopping bag is empty.</p>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between w-100 fw-bold fs-5 text-dark">
                        <span>Total:</span>
                        <span id="cartTotal">৳0.00</span>
                    </div>
                    <a href="https://deencommerce.com" target="_blank" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalBody">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                        <p class="text-muted">Fetching live product details from Deen Commerce...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <h5 class="fw-bold text-white mb-2">Deen Commerce Retail Store</h5>
                    <p class="text-muted small">Your premier destination for urban fashion, washed denim jeans, casual shirts, and outerwear. Connected live to Deen Commerce REST API.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-white mb-2">Quick Navigation</h6>
                    <ul class="list-unstyled text-muted small mb-0">
                        <li><a href="#catalog-section" class="text-white-50 text-decoration-none">Fashion Apparel</a></li>
                        <li><a href="{{ route('woocommerce.products') }}" class="text-white-50 text-decoration-none">Product Catalog</a></li>
                        <li><a href="{{ route('woocommerce.dashboard') }}" class="text-white-50 text-decoration-none">WooCommerce Integration Hub</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-danger px-3 py-2 rounded-pill small">
                        <i class="fas fa-bolt me-1"></i> REST API Target: https://deencommerce.com
                    </span>
                </div>
            </div>
            <div class="my-4 text-center">
                <p class="text-white-50 small mb-2">Supported Payment Gateways & Banking Partners in Bangladesh</p>
                <img src="https://deencommerce.com/wp-content/uploads/2026/03/SSLCommerz-Pay-With-logo-All-Size-01-2048x240-1.png" alt="Payment Methods" class="img-fluid rounded bg-white p-2" style="max-height: 54px;">
            </div>
            <div class="border-top border-secondary pt-3 text-center text-muted small">
                &copy; {{ date('Y') }} Deen Commerce Retail Fashion E-Store. All rights reserved.
            </div>
        </div>
    </footer>


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let cart = [];

    function addToCart(id, name, price, img) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, img, qty: 1 });
        }
        updateCartUI();
        openCartModal();
    }

    function updateCartUI() {
        const count = cart.reduce((acc, item) => acc + item.qty, 0);
        document.getElementById('cartCount').innerText = count;

        const cartList = document.getElementById('cartItemsList');
        const cartTotal = document.getElementById('cartTotal');

        if (cart.length === 0) {
            cartList.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-shopping-bag fa-3x mb-3 opacity-40"></i><p class="mb-0">Your shopping bag is empty.</p></div>';
            cartTotal.innerText = '৳0.00';
            return;
        }

        let total = 0;
        let html = '<div class="d-flex flex-column gap-3">';
        cart.forEach(item => {
            const sub = item.price * item.qty;
            total += sub;
            html += `
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        ${item.img ? `<img src="${item.img}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-2 border">` : '<div class="bg-light p-2 rounded"><i class="fas fa-tshirt"></i></div>'}
                        <div>
                            <div class="fw-bold small text-dark">${item.name}</div>
                            <div class="small text-muted">৳${item.price.toFixed(2)} x ${item.qty}</div>
                        </div>
                    </div>
                    <div class="fw-bold text-dark">৳${sub.toFixed(2)}</div>
                </div>
            `;
        });
        html += '</div>';

        cartList.innerHTML = html;
        cartTotal.innerText = '৳' + total.toFixed(2);
    }

    function openCartModal() {
        const modal = new bootstrap.Modal(document.getElementById('cartModal'));
        modal.show();
    }

    function openProductModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const modalBody = document.getElementById('modalBody');
        modal.show();

        fetch('/store/product/' + id)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.product) {
                    const p = data.product;
                    const img = p.images && p.images[0] ? p.images[0].src : '';
                    modalBody.innerHTML = `
                        <div class="row g-4">
                            <div class="col-md-5">
                                ${img ? `<img src="${img}" class="img-fluid rounded-3 border" alt="${p.name}">` : '<div class="bg-light p-5 text-center rounded"><i class="fas fa-tshirt fa-4x text-muted"></i></div>'}
                            </div>
                            <div class="col-md-7">
                                <span class="badge bg-dark mb-2">WooCommerce Item #${p.id}</span>
                                <h4 class="fw-bold text-dark">${p.name}</h4>
                                <div class="fs-3 fw-bold text-danger mb-3">৳${p.price || '0.00'}</div>
                                <p class="text-muted small mb-3">${p.short_description || p.description || 'Deen Commerce Premium Fashion Product.'}</p>
                                <div class="mb-3">
                                    <span class="badge bg-success me-1">Status: ${p.status || 'publish'}</span>
                                    <span class="badge bg-info text-dark me-1">Stock: ${p.stock_quantity ?? 'Available'}</span>
                                    <span class="badge bg-secondary">SKU: ${p.sku || 'N/A'}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <button onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price || 0}, '${img}')" class="btn btn-dark btn-lg rounded-pill fw-bold">
                                        <i class="fas fa-shopping-bag me-2"></i> Add to Shopping Bag
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    modalBody.innerHTML = '<div class="alert alert-danger">Could not fetch product details.</div>';
                }
            })
            .catch(err => {
                modalBody.innerHTML = '<div class="alert alert-danger">Failed to connect to WooCommerce API.</div>';
            });
    }
    </script>
</body>
</html>