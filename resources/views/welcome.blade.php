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
    <!-- Google Fonts: Outfit, Plus Jakarta Sans & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">

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
                <span class="deen-leather-patch ms-1">Denim Apparel</span>
            </a>

            <!-- Mobile Header Action Buttons -->
            <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
                <button class="btn btn-outline-warning btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" onclick="openMobileSearchModal()" title="Instant Search">
                    <span class="material-symbols-outlined fs-5">search</span>
                </button>
                <button class="btn btn-warning btn-sm rounded-circle p-2 position-relative d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" onclick="openCartModal()" title="Cart">
                    <span class="material-symbols-outlined fs-5 text-dark">shopping_bag</span>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="headerMobileCartBadge">0</span>
                </button>
            </div>



            <button class="navbar-toggler text-white border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#deenRetailNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="deenRetailNavbar">
                <!-- Search Bar with Category Select & Predictive Dropdown Wrapper -->
                <form method="GET" action="{{ route('store.index') }}" class="mx-auto my-2 my-lg-0 deen-search-wrapper" style="max-width: 520px; width: 100%;">
                    <div class="input-group">
                        <select name="category" class="form-select bg-dark text-white-50 border-secondary rounded-start-pill text-truncate d-none d-sm-block" style="max-width: 140px; font-size: 0.85rem;">
                            <option value="">All Categories</option>
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat['id'] }}" {{ ($selectedCategory == $cat['id']) ? 'selected' : '' }}>
                                        {{ $cat['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" id="desktopHeaderSearchInput" name="search" class="form-control bg-dark text-white border-secondary px-3" placeholder="Search jeans, shirts, polos..." value="{{ $searchQuery ?? '' }}" autocomplete="off" onkeyup="handleHeaderPredictiveSearch(this.value)">
                        <button class="btn btn-primary rounded-end-pill px-3" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                    <!-- Desktop Predictive Dropdown Container -->
                    <div id="desktopSearchDropdown" class="deen-search-results-dropdown"></div>
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
                        <div class="deen-perk-icon"><span class="material-symbols-outlined fs-2 text-warning">local_shipping</span></div>
                        <div>
                            <div class="deen-perk-title">Free Fast Shipping</div>
                            <div class="deen-perk-desc">On orders over ৳2,000</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><span class="material-symbols-outlined fs-2 text-success">verified</span></div>
                        <div>
                            <div class="deen-perk-title">100% Authentic</div>
                            <div class="deen-perk-desc">Original Deen Quality</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><span class="material-symbols-outlined fs-2 text-info">autorenew</span></div>
                        <div>
                            <div class="deen-perk-title">7 Days Returns</div>
                            <div class="deen-perk-desc">Hassle-free Exchange</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="deen-perk-item">
                        <div class="deen-perk-icon"><span class="material-symbols-outlined fs-2 text-primary">support_agent</span></div>
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

            <!-- Flash Sale Ticker Alert Banner -->
            <div class="deen-flash-sale-banner mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-fire fa-lg text-warning animate-bounce"></i>
                    <span><strong>FLASH SALE 2026:</strong> Extra ৳300 OFF on orders over ৳2,500! Code: <span class="badge bg-warning text-dark font-monospace">DEEN2026</span></span>
                </div>
                <button type="button" class="btn-close btn-close-white btn-sm" onclick="this.parentElement.remove()"></button>
            </div>

            <!-- Category Filter Bar & Collapsible Mobile Filter Trigger -->
            <div class="d-flex align-items-center justify-content-between gap-2 pb-3 mb-4">
                @if(!empty($categories))
                    <div class="d-flex align-items-center gap-2 overflow-auto no-scrollbar flex-grow-1 me-2">
                        <a href="{{ route('store.index') }}" class="deen-fashion-chip {{ empty($selectedCategory) ? 'active' : '' }}">
                            <span class="material-symbols-outlined fs-5">grid_view</span> All
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('store.index', ['category' => $cat['id']]) }}" class="deen-fashion-chip {{ ($selectedCategory == $cat['id']) ? 'active' : '' }}">
                                <span class="material-symbols-outlined fs-5">checkroom</span> {{ $cat['name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Collapsible Mobile Filter Drawer Trigger Button -->
                <button type="button" class="deen-filter-trigger-btn flex-shrink-0" onclick="openMobileFilterDrawer()">
                    <span class="material-symbols-outlined fs-5">tune</span> <span class="d-none d-sm-inline">Filter & Sort</span>
                </button>
            </div>


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

            <!-- Products Grid (2-Column Mobile Grid for Amazon/Daraz Industry Standard UX) -->
            <div class="row g-2 g-sm-3 g-md-4 mb-5">
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
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="deen-retail-card">
                            <div class="deen-retail-img-box">
                                @if($image)
                                    <img src="{{ $image }}" loading="lazy" class="deen-retail-img" alt="{{ $product['name'] }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-tshirt fa-4x text-secondary opacity-40"></i>
                                    </div>
                                @endif

                                <!-- Wishlist Heart Toggle Button -->
                                <button type="button" class="deen-wishlist-btn" data-id="{{ $product['id'] }}" onclick="toggleWishlist({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}', this)" title="Save to Favorites">
                                    <i class="fas fa-heart"></i>
                                </button>

                                <!-- 1-Tap Quick View Overlay Button -->
                                <button type="button" class="deen-quickview-btn" onclick="openProductModal({{ $product['id'] }})">
                                    <i class="fas fa-eye me-1"></i> Quick View
                                </button>

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

                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="deen-rating-stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                        <span class="text-muted ms-1 small fw-bold">4.9 (248)</span>
                                    </div>

                                    <!-- Stock Urgency Badge -->
                                    <span class="deen-urgency-badge">
                                        <i class="fas fa-bolt"></i> Only {{ rand(2, 4) }} left!
                                    </span>
                                </div>

                                <!-- Inline Color & Size Variant Preview -->
                                <div class="deen-variant-row">
                                    <span class="deen-variant-dot" style="background: #1e293b;" title="Indigo Denim"></span>
                                    <span class="deen-variant-dot" style="background: #0f172a;" title="Obsidian Black"></span>
                                    <span class="deen-variant-dot" style="background: #64748b;" title="Washed Grey"></span>
                                    <div class="ms-auto d-flex gap-1">
                                        <span class="deen-mini-size-chip">30</span>
                                        <span class="deen-mini-size-chip">32</span>
                                        <span class="deen-mini-size-chip">34</span>
                                    </div>
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

            <!-- 6. CUSTOMER TESTIMONIALS & VERIFIED USER REVIEWS -->
            <section class="my-5 pt-3">
                <div class="text-center mb-4">
                    <span class="badge bg-warning text-dark font-monospace px-3 py-2 rounded-pill uppercase fw-bold mb-2">Real Customer Love</span>
                    <h3 class="fw-bold text-dark mb-1">What Bangladesh Shoppers Say</h3>
                    <p class="text-muted small">Over 15,000+ verified retail fashion buyers across Dhaka, Chittagong & Sylhet</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="deen-testimonial-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop" class="deen-testimonial-avatar" alt="Reviewer">
                                <div>
                                    <div class="fw-bold text-white">Nusrat Jahan</div>
                                    <div class="small text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
                                </div>
                            </div>
                            <p class="small text-white-50 mb-0">"The 13.5oz Raw Washed Denim Jeans fit amazingly! Delivery was fast in Gulshan (24 hours). 100% authentic quality!"</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="deen-testimonial-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop" class="deen-testimonial-avatar" alt="Reviewer">
                                <div>
                                    <div class="fw-bold text-white">Ayman Rahman</div>
                                    <div class="small text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
                                </div>
                            </div>
                            <p class="small text-white-50 mb-0">"Ordered Oxford Shirts and Polo tees. bKash payment was smooth and product fabric quality exceeded expectations."</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="deen-testimonial-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop" class="deen-testimonial-avatar" alt="Reviewer">
                                <div>
                                    <div class="fw-bold text-white">Sabrina Islam</div>
                                    <div class="small text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
                                </div>
                            </div>
                            <p class="small text-white-50 mb-0">"Deen Commerce mobile app checkout was super quick. Tracked my Steadfast courier delivery right in the app!"</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- INSTAGRAM SHOPPABLE FEED (#deen-insta-feed) -->
            <section class="my-5" id="deen-insta-feed">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1"><i class="fab fa-instagram text-danger me-2"></i> @DEENCOMMERCE Shoppable Lookbook</h4>
                        <p class="text-muted small mb-0">Tag us on Instagram to get featured & win ৳1,000 shopping vouchers</p>
                    </div>
                    <a href="https://instagram.com" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill fw-bold">Follow Us &rarr;</a>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="deen-insta-card">
                            <img src="https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg" class="deen-insta-img" alt="Insta Feed 1">
                            <div class="deen-insta-hotspot" onclick="openProductModal(1)" title="Shop This Look">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="deen-insta-overlay">
                                <div class="small text-white fw-bold">Washed Denim Look • Tagged @tanvir_dhaka</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="deen-insta-card">
                            <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Insta Feed 2">
                            <div class="deen-insta-hotspot" onclick="openProductModal(2)" title="Shop This Look">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="deen-insta-overlay">
                                <div class="small text-white fw-bold">Urban Slim Oxford • Tagged @samir_urban</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="deen-insta-card">
                            <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Insta Feed 3">
                            <div class="deen-insta-hotspot" onclick="openProductModal(3)" title="Shop This Look">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="deen-insta-overlay">
                                <div class="small text-white fw-bold">Polo Casual Fit • Tagged @rakib_style</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="deen-insta-card">
                            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Insta Feed 4">
                            <div class="deen-insta-hotspot" onclick="openProductModal(4)" title="Shop This Look">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="deen-insta-overlay">
                                <div class="small text-white fw-bold">Streetwear Outerwear • Tagged @faisal_bd</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 7. "SHOP NOW" PROMOTIONAL BANNER FOR SALE ITEMS -->
            <section class="my-5">
                <div class="deen-shop-now-banner text-center text-md-start">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <span class="badge bg-warning text-dark font-monospace fw-bold px-3 py-2 rounded-pill mb-3">Limited Time Flash Sale</span>
                            <h2 class="display-6 fw-extrabold text-white mb-2">Up to 40% OFF Premium Urban Apparel</h2>
                            <p class="text-white-50 fs-5 mb-4">Elevate your street wardrobe with authentic Bangladeshi crafted denim and cotton shirts.</p>
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <a href="#catalog-section" class="btn btn-warning btn-lg rounded-pill fw-bold px-5 text-dark shadow">
                                    Shop Sale Items Now <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                <span class="text-white-50 small"><i class="fas fa-truck text-warning me-1"></i> Free Shipping Over ৳2,000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- RECENTLY VIEWED PRODUCTS SECTION -->
    <section id="recentlyViewedSection" class="py-4 bg-light border-top border-bottom my-4 d-none">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-warning me-2"></i> Recently Viewed Products</h5>
                <button onclick="localStorage.removeItem('deen_recently_viewed'); renderRecentlyViewed();" class="btn btn-sm btn-link text-muted p-0 text-decoration-none small">Clear History</button>
            </div>
            <div class="d-flex gap-3 overflow-auto pb-2 no-scrollbar" id="recentlyViewedContainer"></div>
        </div>
    </section>

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

    <!-- STICKY MOBILE BOTTOM THUMB-ZONE NAVIGATION BAR -->
    <div class="deen-mobile-bottom-nav">
        <a href="{{ route('store.index') }}" class="deen-mobile-nav-item active">
            <span class="material-symbols-outlined nav-icon">storefront</span>
            <span>Shop</span>
        </a>
        <a href="{{ route('store.categories') }}" class="deen-mobile-nav-item">
            <span class="material-symbols-outlined nav-icon">grid_view</span>
            <span>Categories</span>
        </a>
        <a href="#" onclick="event.preventDefault(); openMobileSearchModal();" class="deen-mobile-nav-item">
            <span class="material-symbols-outlined nav-icon">search</span>
            <span>Search</span>
        </a>
        <a href="#" onclick="event.preventDefault(); openCartModal();" class="deen-mobile-nav-item">
            <span class="material-symbols-outlined nav-icon">shopping_bag</span>
            <span>Bag</span>
            <span class="deen-mobile-nav-badge" id="bottomNavCartBadge">0</span>
        </a>
        <a href="{{ route('account.dashboard') }}" class="deen-mobile-nav-item">
            <span class="material-symbols-outlined nav-icon">person</span>
            <span>Account</span>
        </a>
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

    <!-- COLLAPSIBLE MOBILE FILTER DRAWER MODAL -->
    <div class="modal fade" id="mobileFilterDrawer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-md">
            <div class="modal-content border-0 shadow-lg bg-dark text-white rounded-4 overflow-hidden">
                <div class="modal-header border-secondary p-3">
                    <h5 class="modal-title fw-bold text-white"><span class="material-symbols-outlined align-middle me-2 text-warning">tune</span> Filter & Sort Apparel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('store.index') }}" class="modal-body p-4">
                    <!-- Category Select -->
                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold mb-2"><i class="fas fa-list me-1"></i> Category Collection</label>
                        <select name="category" class="form-select bg-dark text-white border-secondary rounded-3">
                            <option value="">All Fashion Categories</option>
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat['id'] }}" {{ ($selectedCategory == $cat['id']) ? 'selected' : '' }}>
                                        {{ $cat['name'] }} ({{ $cat['count'] ?? 0 }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold mb-2"><i class="fas fa-search me-1"></i> Keywords / Search</label>
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="e.g. Raw Washed Jeans, Polo..." value="{{ $searchQuery ?? '' }}">
                    </div>

                    <!-- Stock Status Option -->
                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold mb-2"><i class="fas fa-box-check me-1"></i> Stock Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="filterInStockOnly" checked>
                            <label class="form-check-label text-white small" for="filterInStockOnly">In Stock Items Only</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4 pt-3 border-top border-secondary">
                        <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark shadow">
                            <span class="material-symbols-outlined align-middle me-1">check_circle</span> Apply Filters
                        </button>
                        <a href="{{ route('store.index') }}" class="btn btn-outline-light rounded-pill fw-bold">
                            Reset All Filters
                        </a>
                    </div>
                </form>
            </div>
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
                <div class="col-md-4 text-md-end d-flex flex-column align-items-md-end gap-2">
                    <span class="badge bg-danger px-3 py-2 rounded-pill small">
                        <i class="fas fa-bolt me-1"></i> REST API Target: https://deencommerce.com
                    </span>
                    <a href="https://t.me/DEEN_Commerce_bot" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold text-white">
                        <i class="fab fa-telegram me-1"></i> 24/7 Support: @DEEN_Commerce_bot
                    </a>
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
    let cart = getStoredCart();

    function addToCart(id, name, price, img) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, img, qty: 1 });
        }
        localStorage.setItem('deen_cart', JSON.stringify(cart));
        syncCartBadges();
        updateCartUI();
        openCartModal();
    }

    function updateCartUI() {
        cart = getStoredCart();
        const count = cart.reduce((acc, item) => acc + item.qty, 0);
        syncCartBadges();

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
    let headerSearchDebounce;

    function handleHeaderPredictiveSearch(query) {
        clearTimeout(headerSearchDebounce);
        const dropdown = document.getElementById('desktopSearchDropdown');

        if (!query || query.trim().length < 2) {
            dropdown.classList.remove('show');
            dropdown.innerHTML = '';
            return;
        }

        headerSearchDebounce = setTimeout(() => {
            fetch('/store/search/suggestions?q=' + encodeURIComponent(query.trim()))
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.suggestions && data.suggestions.length > 0) {
                        let html = '';
                        data.suggestions.forEach(item => {
                            const img = item.image || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
                            html += `
                                <a href="${item.detail_url}" class="deen-search-item">
                                    <img src="${img}" class="deen-search-thumb" alt="${item.name}">
                                    <div class="deen-search-info">
                                        <div class="deen-search-name">${item.name}</div>
                                        <div class="deen-search-meta">
                                            <span class="deen-search-price">৳${item.price.toFixed(2)}</span>
                                            <span class="badge bg-success rounded-pill px-2">In Stock</span>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-warning fs-5">arrow_forward</span>
                                </a>
                            `;
                        });
                        dropdown.innerHTML = html;
                        dropdown.classList.add('show');
                    } else {
                        dropdown.classList.remove('show');
                        dropdown.innerHTML = '';
                    }
                })
                .catch(() => {
                    dropdown.classList.remove('show');
                });
        }, 300);
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

    /* TELEGRAM CHATBOT POPOVER TOGGLE */
    function toggleTelegramChatPopover() {
        const popover = document.getElementById('telegramChatPopover');
        if (popover) {
            popover.classList.toggle('show');
        }
    }

    /* PREDICTIVE MOBILE SEARCH LOGIC */
    let searchDebounceTimer;

    function openMobileSearchModal() {
        const modal = new bootstrap.Modal(document.getElementById('mobileSearchModal'));
        modal.show();
        setTimeout(() => {
            document.getElementById('predictiveSearchInput')?.focus();
        }, 400);
    }

    function openMobileFilterDrawer() {
        const modal = new bootstrap.Modal(document.getElementById('mobileFilterDrawer'));
        modal.show();
    }

    function setPredictiveSearch(val) {
        const input = document.getElementById('predictiveSearchInput');
        if (input) {
            input.value = val;
            handlePredictiveSearch(val);
        }
    }

    function clearPredictiveSearch() {
        const input = document.getElementById('predictiveSearchInput');
        if (input) input.value = '';
        const list = document.getElementById('predictiveResultsList');
        if (list) {
            list.innerHTML = `
                <div class="text-center py-4 text-white-50">
                    <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
                    <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
                </div>
            `;
        }
    }

    function handlePredictiveSearch(query) {
        clearTimeout(searchDebounceTimer);
        const container = document.getElementById('predictiveResultsList');

        if (!query || query.trim().length < 2) {
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-4 text-white-50">
                        <span class="material-symbols-outlined fs-1 opacity-40 mb-2">pageview</span>
                        <p class="mb-0 small">Start typing to see instant fashion suggestions...</p>
                    </div>
                `;
            }
            return;
        }

        if (container) {
            container.innerHTML = `
                <div class="text-center py-4 text-white-50">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-warning"></i>
                    <p class="mb-0 small">Searching Deen Commerce catalog...</p>
                </div>
            `;
        }

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
                        if (container) container.innerHTML = html;
                    } else if (container) {
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
                    if (container) container.innerHTML = '<div class="alert alert-danger py-2 small">Error fetching search results.</div>';
                });
        }, 300);
    }

    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('desktopSearchDropdown');
        if (dropdown && !e.target.closest('.deen-search-wrapper')) {
            dropdown.classList.remove('show');
        }

        const tgWidget = e.target.closest('.deen-telegram-widget-wrapper');
        const tgPopover = document.getElementById('telegramChatPopover');
        if (!tgWidget && tgPopover && tgPopover.classList.contains('show')) {
            tgPopover.classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        syncCartBadges();
    });
    </script>
</body>
</html>