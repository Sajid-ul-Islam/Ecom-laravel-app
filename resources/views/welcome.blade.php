@extends('layouts.app')

@section('content')
 <!-- Retail Hero Editorial Showcase (Vast Negative Space & Thin Structured Frame) -->
 <section class="deen-fashion-hero">
 <div class="container">
 <div id="deenHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
 <div class="carousel-inner">
 @if(!empty($heroSlides))
 @foreach($heroSlides as $index => $slide)
 @php
 $slideImg = $slide['images'][0]['src'] ?? 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';
 $slidePrice = !empty($slide['price']) ? '৳' . number_format((float)$slide['price']) : '৳2,490';
 $regPrice = !empty($slide['regular_price']) ? '৳' . number_format((float)$slide['regular_price']) : null;
 $tagline = $slide['tagline'] ?? ('Curated Look #' . ($slide['id'] ?? $index+1));
 @endphp
 <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
 <div class="row align-items-center g-5 py-2">
 <div class="col-lg-7">
 <div class="deen-vibrant-pill indigo mb-3">
 <span class="material-symbols-outlined fs-6">auto_awesome</span> {{ $tagline }}
 </div>
 <h1 class="deen-hero-heading">
 <span class="deen-gradient-text">{{ $slide['name'] }}</span>
 </h1>
 <p class="deen-hero-desc">
 {!! Str::limit(strip_tags($slide['short_description'] ?? $slide['description'] ?? 'Discover authentic Bangladesh washed denim, tailored oxford shirts, and timeless urban apparel crafted with quiet precision.'), 160) !!}
 </p>
 <div class="d-flex flex-wrap align-items-center gap-3">
 <a href="{{ route('store.product.detail', $slide['id'] ?? 1) }}" class="btn-deen-vibrant">
 <span>View Collection Piece</span>
 <i class="fas fa-arrow-right ms-1"></i>
 </a>
 <a href="#catalog-section" class="btn-deen-outline">
 Browse Catalog
 </a>
 </div>
 </div>

 <div class="col-lg-5">
 <div class="deen-hero-showcase-card text-center position-relative">
 <span class="deen-vibrant-pill indigo position-absolute top-0 end-0 m-3 z-3">Authentic Line</span>
 <div class="rounded-4 overflow-hidden mb-3" style="height: 270px; background: #f8fafc;">
 <img src="{{ $slideImg }}" class="w-100 h-100 object-fit-cover" alt="{{ $slide['name'] }}">
 </div>
 <div class="pt-2">
 <h4 class="fw-bold text-dark mb-1 text-truncate font-display" style="font-size: 1.2rem;">{{ $slide['name'] }}</h4>
 <div class="text-muted small mb-2"><i class="fas fa-circle-check text-success me-1"></i> Live WooCommerce Synced</div>
 <div class="fs-3 fw-bold text-dark font-display mb-3">
 {{ $slidePrice }}
 @if($regPrice && $regPrice !== $slidePrice)
 <small class="fs-6 text-muted text-decoration-line-through fw-normal ms-1">{{ $regPrice }}</small>
 @endif
 </div>
 <a href="{{ route('store.product.detail', $slide['id'] ?? 1) }}" class="btn-deen-primary w-100 py-2.5">
 Explore Details <i class="fas fa-arrow-right ms-1"></i>
 </a>
 </div>
 </div>
 </div>
 </div>
 </div>
 @endforeach
 @endif
 </div>

 @if(!empty($heroSlides) && count($heroSlides) > 1)
 <div class="d-flex align-items-center justify-content-center gap-2 mt-4">
 @foreach($heroSlides as $index => $slide)
 <button type="button" data-bs-target="#deenHeroCarousel" data-bs-slide-to="{{ $index }}" class="btn p-0 rounded-pill {{ $index === 0 ? 'bg-dark' : 'bg-secondary opacity-40' }}" aria-label="Slide {{ $index + 1 }}"></button>
 @endforeach
 </div>
 @endif
 </div>
 </div>
 </section>

 <!-- Retail Perks Value Bar (4 Distinct Vibrant Cards with Luminous Icons) -->
 <div class="container deen-perks-box">
 <div class="row g-3 g-lg-4">
 <div class="col-12 col-sm-6 col-lg-3">
 <div class="deen-perk-card deen-pastel-azure">
 <div class="deen-perk-icon-wrap" style="background: #dbeafe; color: #1d4ed8;">
 <span class="material-symbols-outlined fs-4">local_shipping</span>
 </div>
 <div>
 <div class="deen-perk-title">Complimentary Shipping</div>
 <div class="deen-perk-desc">On nationwide orders over ৳2,000</div>
 </div>
 </div>
 </div>
 <div class="col-12 col-sm-6 col-lg-3">
 <div class="deen-perk-card deen-pastel-sage">
 <div class="deen-perk-icon-wrap" style="background: #d1fae5; color: #047857;">
 <span class="material-symbols-outlined fs-4">verified</span>
 </div>
 <div>
 <div class="deen-perk-title">100% Authentic Denim</div>
 <div class="deen-perk-desc">13.5oz ring-spun cotton twill</div>
 </div>
 </div>
 </div>
 <div class="col-12 col-sm-6 col-lg-3">
 <div class="deen-perk-card deen-pastel-sand">
 <div class="deen-perk-icon-wrap" style="background: #fef3c7; color: #b45309;">
 <span class="material-symbols-outlined fs-4">published_with_changes</span>
 </div>
 <div>
 <div class="deen-perk-title">7 Days Exchange</div>
 <div class="deen-perk-desc">Hassle-free size replacement</div>
 </div>
 </div>
 </div>
 <div class="col-12 col-sm-6 col-lg-3">
 <div class="deen-perk-card deen-pastel-lavender">
 <div class="deen-perk-icon-wrap" style="background: #ede9fe; color: #6d28d9;">
 <span class="material-symbols-outlined fs-4">support_agent</span>
 </div>
 <div>
 <div class="deen-perk-title">Personal Concierge</div>
 <div class="deen-perk-desc">Instant Telegram & WhatsApp care</div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- Main Fashion Storefront (Vast Negative Space) -->
 <main class="deen-section pt-0" id="catalog-section">
 <div class="container">

 <!-- Flash Sale Ticker Alert Banner (Pastel Sand Accent) -->
 <div class="deen-flash-sale-banner mb-5">
 <div class="d-flex align-items-center gap-3">
 <span class="deen-pastel-pill sand py-1 px-2">Limited Season Offer</span>
 <span>Extra ৳300 OFF on orders over ৳2,500 with promo code: <strong>DEEN2026</strong></span>
 </div>
 <button type="button" class="btn-close btn-sm" onclick="this.parentElement.remove()"></button>
 </div>

 <!-- Category Filter Bar & Collapsible Mobile Filter Trigger -->
 <div class="d-flex align-items-center justify-content-between gap-3 pb-3 mb-4">
 @if(!empty($categories))
 <div class="d-flex align-items-center gap-2 overflow-auto no-scrollbar flex-grow-1 me-2 py-1">
 <a href="{{ route('store.index') }}" class="deen-fashion-chip {{ empty($selectedCategory) ? 'active' : '' }}">
 <span class="material-symbols-outlined fs-6">grid_view</span> All Items
 </a>
 @foreach($categories as $cat)
 <a href="{{ route('store.index', ['category' => $cat['id']]) }}" class="deen-fashion-chip {{ ($selectedCategory == $cat['id']) ? 'active' : '' }}">
 <span class="material-symbols-outlined fs-6">checkroom</span> {{ $cat['name'] }}
 </a>
 @endforeach
 </div>
 @endif

 <!-- Collapsible Mobile Filter Drawer Trigger Button -->
 <button type="button" class="deen-filter-trigger-btn flex-shrink-0" onclick="openMobileFilterDrawer()">
 <span class="material-symbols-outlined fs-6">tune</span> <span class="d-none d-sm-inline">Filter & Sort</span>
 </button>
 </div>

 <!-- Section Header (Architectural Typography) -->
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-5">
 <div>
 <div class="deen-eyebrow text-primary">Curated Collection</div>
 <h2 class="fw-bold text-dark mb-1 font-display fs-2">Featured Apparel & Washed Denim</h2>
 <p class="text-secondary small mb-0">Browse modern fashion lines synced live from Deen Commerce inventory</p>
 </div>
 <div class="deen-vibrant-pill emerald py-2 px-3">
 <i class="fas fa-bolt text-success"></i> {{ count($products) }} Products Live
 </div>
 </div>

 <!-- Products Grid (Calm, Clean Professionalism) -->
 <div class="row g-3 g-sm-4 g-lg-4 mb-5">
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
 <div class="deen-retail-card position-relative">
 <div class="deen-retail-img-box deen-card-video-box">
 @if($image)
 <img src="{{ $image }}" loading="lazy" class="deen-retail-img deen-card-main-img" alt="{{ $product['name'] }}">
 @else
 <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
 <i class="fas fa-tshirt fa-3x text-secondary opacity-30"></i>
 </div>
 @endif

 <!-- Subtle Fabric & Motion Video Snippet -->
 <video class="deen-card-video" muted loop playsinline preload="none" poster="{{ $image }}">
 <source src="https://assets.mixkit.co/videos/preview/mixkit-fashion-model-in-a-denim-jacket-and-pants-40763-small.mp4" type="video/mp4">
 </video>
 <span class="deen-video-badge"><i class="fas fa-play"></i> Motion</span>

 <!-- Wishlist Heart Toggle Button -->
 <button type="button" class="deen-wishlist-btn" data-id="{{ $product['id'] }}" onclick="toggleWishlist({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}', this)" title="Save to Favorites">
 <i class="fas fa-heart"></i>
 </button>

 @if($discountPercent > 0)
 <span class="deen-discount-ribbon">-{{ $discountPercent }}%</span>
 @endif

 @if(!($stockStatus === 'instock' || ($stockQty && $stockQty > 0)))
 <span class="deen-soldout-badge">Sold Out</span>
 @endif
 </div>

 <div class="deen-retail-body">
 <!-- Instant Fabric & Wash Swatches --><div class="deen-card-swatches" role="radiogroup" aria-label="Select fabric wash color">
 <button type="button" class="deen-swatch-dot swatch-indigo active" onclick="swapCardImage(this, '{{ $image }}')" title="Raw Indigo" role="radio" aria-checked="true" aria-label="Raw Indigo"></button>
 <button type="button" class="deen-swatch-dot swatch-vintage" onclick="swapCardImage(this, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&auto=format&fit=crop')" title="Vintage Light Wash" role="radio" aria-checked="false" aria-label="Vintage Light Wash"></button>
 <button type="button" class="deen-swatch-dot swatch-black" onclick="swapCardImage(this, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop')" title="Stonewashed Black" role="radio" aria-checked="false" aria-label="Stonewashed Black"></button>
 <button type="button" class="deen-swatch-dot swatch-ecru" onclick="swapCardImage(this, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop')" title="Raw Ecru" role="radio" aria-checked="false" aria-label="Raw Ecru"></button>
 </div>

 <a href="{{ route('store.product.detail', $product['id']) }}" class="deen-retail-title" title="{{ $product['name'] }}">
 {{ $product['name'] }}
 </a>

 <div class="d-flex align-items-center justify-content-between mb-2">
 <div class="deen-rating-stars">
 <i class="fas fa-star"></i>
 <i class="fas fa-star"></i>
 <i class="fas fa-star"></i>
 <i class="fas fa-star"></i>
 <i class="fas fa-star-half-alt"></i>
 <span class="text-muted ms-1 small">4.9</span>
 </div>
 <span class="small text-muted d-inline-flex align-items-center gap-1">
 @if($stockStatus === 'instock' || ($stockQty && $stockQty > 0))
 <span class="deen-stock-dot"></span> In Stock
 @else
 <span class="deen-stock-dot soldout"></span> Sold Out
 @endif
 </span>
 </div>

 <div class="deen-retail-price-row mb-3">
 <span class="deen-retail-price">৳{{ number_format($price, 2) }}</span>
 @if($regularPrice && $regularPrice > $price)
 <span class="deen-retail-old-price">৳{{ number_format($regularPrice, 2) }}</span>
 @endif
 </div>

 <div class="mt-auto d-grid gap-2">
 <div class="d-flex gap-2">
 <button onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}')" class="btn-deen-primary flex-grow-1 py-2">
 <i class="fas fa-shopping-cart me-1 opacity-75"></i> Add
 </button>
 <button type="button" onclick="openQuickAddFlyout({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}')" class="deen-quick-add-btn" title="Quick Size Pick">
 Quick Add +
 </button>
 </div>
 <a href="{{ route('store.product.detail', $product['id']) }}" class="deen-btn-card-action">
 Details <i class="fas fa-arrow-right ms-1"></i>
 </a>
 </div>
 </div>
 </div>
 </div>
 @empty
 <div class="col-12 text-center py-5">
 <div class="deen-frame p-5">
 <i class="fas fa-tshirt fa-3x text-muted opacity-40 mb-3"></i>
 <h4>No Products Found</h4>
 <p class="text-muted">No items matched your current filter criteria.</p>
 <a href="{{ route('store.index') }}" class="btn-deen-primary">Reset Filter</a>
 </div>
 </div>
 @endforelse
 </div>

 <!-- Global 2-Step Sizing Estimator Banner Trigger -->
 <div class="deen-frame deen-pastel-azure p-4 p-md-5 my-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
 <div>
 <span class="deen-pastel-pill azure mb-2">Precision Sizing Tool</span>
 <h3 class="deen-title-md mb-1">Unsure About Your Exact Waist or Chest Size?</h3>
 <p class="text-secondary small mb-0">
 Take our 10-second interactive body metric estimator. Calibrated for authentic Bangladeshi male dimensions.
 </p>
 </div>
 <button type="button" class="btn-deen-primary py-3 px-4" onclick="openSizeCalculator()">
 <i class="fas fa-ruler-combined me-2"></i> Launch Sizing Estimator
 </button>
 </div>

 <!-- ==========================================================================
 MEN'S APPAREL UX 2: SHOP-THE-LOOK HOTSPOTS & OUTFIT BUNDLE SHOWCASE
 ========================================================================== -->
 <section class="deen-section pt-0">
 <div class="d-flex align-items-center justify-content-between mb-4">
 <div>
 <div class="deen-eyebrow">Interactive Lookbook</div>
 <h3 class="fw-semibold text-dark mb-1">Shop The Complete Urban Ensemble</h3>
 <p class="text-secondary small mb-0">Tap the interactive garment hotspots on the lookbook model to inspect or bundle the full outfit</p>
 </div>
 <a href="{{ route('store.categories') }}" class="btn-deen-outline py-2 px-3">Explore All Lines &rarr;</a>
 </div>

 <div class="row g-4 align-items-stretch">
 <!-- Left: Large Look Canvas with Pulsing Hotspots -->
 <div class="col-lg-7">
 <div class="deen-look-canvas" style="min-height: 520px; height: 100%;">
 <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=900&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Deen Commerce Studio Look" style="min-height: 520px;">

 <!-- Hotspot 1: Denim Jacket (Top) -->
 <div class="deen-hotspot-pin" role="button" tabindex="0" aria-label="View Raw Selvedge Denim Jacket - ৳3,450" onkeydown="if(event.key==='Enter'||event.key===' ')this.click()" style="top: 28%; left: 46%;">
 <div class="deen-hotspot-ring"></div>
 <i class="fas fa-plus"></i>
 <div class="deen-hotspot-popover">
 <div class="d-flex align-items-center gap-2 mb-2">
 <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=200&auto=format&fit=crop" loading="lazy" alt="Raw Selvedge Denim Jacket" class="rounded-2 border">
 <div>
 <div class="fw-bold small text-dark">Raw Selvedge Denim Jacket</div>
 <div class="fw-bold text-primary small">৳3,450.00</div>
 </div>
 </div>
 <button type="button" class="btn-deen-primary w-100 py-1" onclick="addToCart(101, 'Raw Selvedge Denim Jacket', 3450, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=200&auto=format&fit=crop')">
 <i class="fas fa-shopping-cart me-1"></i> Add Jacket
 </button>
 </div>
 </div>

 <!-- Hotspot 2: Oxford Shirt (Mid) -->
 <div class="deen-hotspot-pin" role="button" tabindex="0" aria-label="View Structured Oxford Button-Down - ৳1,850" onkeydown="if(event.key==='Enter'||event.key===' ')this.click()" style="top: 48%; left: 52%;">
 <div class="deen-hotspot-ring"></div>
 <i class="fas fa-plus"></i>
 <div class="deen-hotspot-popover">
 <div class="d-flex align-items-center gap-2 mb-2">
 <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=200&auto=format&fit=crop" loading="lazy" alt="Structured Oxford Button-Down" class="rounded-2 border">
 <div>
 <div class="fw-bold small text-dark">Structured Oxford Button-Down</div>
 <div class="fw-bold text-primary small">৳1,850.00</div>
 </div>
 </div>
 <button type="button" class="btn-deen-primary w-100 py-1" onclick="addToCart(102, 'Structured Oxford Button-Down', 1850, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=200&auto=format&fit=crop')">
 <i class="fas fa-shopping-cart me-1"></i> Add Shirt
 </button>
 </div>
 </div>

 <!-- Hotspot 3: Washed Jeans (Bottom) -->
 <div class="deen-hotspot-pin" role="button" tabindex="0" aria-label="View 13.5oz Washed Slim Denim Jeans - ৳2,250" onkeydown="if(event.key==='Enter'||event.key===' ')this.click()" style="top: 76%; left: 48%;">
 <div class="deen-hotspot-ring"></div>
 <i class="fas fa-plus"></i>
 <div class="deen-hotspot-popover">
 <div class="d-flex align-items-center gap-2 mb-2">
 <img src="https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg" loading="lazy" alt="13.5oz Washed Slim Denim Jeans" class="rounded-2 border">
 <div>
 <div class="fw-bold small text-dark">13.5oz Washed Slim Denim Jeans</div>
 <div class="fw-bold text-primary small">৳2,250.00</div>
 </div>
 </div>
 <button type="button" class="btn-deen-primary w-100 py-1" onclick="addToCart(103, '13.5oz Washed Slim Denim Jeans', 2250, 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg')">
 <i class="fas fa-shopping-cart me-1"></i> Add Jeans
 </button>
 </div>
 </div>
 </div>
 </div>

 <!-- Right: Styled Outfit Bundle Breakdown -->
 <div class="col-lg-5 d-flex flex-column justify-content-between">
 <div class="deen-frame deen-pastel-linen p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
 <div>
 <span class="deen-pastel-pill sage mb-2">Curated Men's Outfit #04</span>
 <h3 class="deen-title-sm mb-3">The Architectural Dhaka Indigo Look</h3>
 <p class="text-secondary small mb-4">
 A balanced urban wardrobe combination featuring our raw selvedge outerwear, combed cotton shirt, and vintage washed slim jeans.
 </p>

 <div class="d-flex flex-column gap-3 mb-4">
 <div class="d-flex align-items-center justify-content-between pb-2 border-bottom">
 <div class="d-flex align-items-center gap-3">
 <span class="badge bg-dark rounded-circle">1</span>
 <span class="small fw-semibold text-dark">Raw Selvedge Denim Jacket</span>
 </div>
 <span class="small fw-bold text-dark font-display">৳3,450.00</span>
 </div>
 <div class="d-flex align-items-center justify-content-between pb-2 border-bottom">
 <div class="d-flex align-items-center gap-3">
 <span class="badge bg-dark rounded-circle">2</span>
 <span class="small fw-semibold text-dark">Structured Oxford Button-Down</span>
 </div>
 <span class="small fw-bold text-dark font-display">৳1,850.00</span>
 </div>
 <div class="d-flex align-items-center justify-content-between pb-2 border-bottom">
 <div class="d-flex align-items-center gap-3">
 <span class="badge bg-dark rounded-circle">3</span>
 <span class="small fw-semibold text-dark">13.5oz Washed Slim Denim Jeans</span>
 </div>
 <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white">
 <div class="d-flex align-items-center gap-2.5">
 <div class="deen-avatar-md deen-avatar-rounded">
 <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=100&auto=format&fit=crop" loading="lazy" alt="Raw Selvedge Denim Jacket" class="w-100 h-100 object-fit-cover rounded-2">
 </div>
 <div>
 <div class="fw-bold small text-dark">Raw Selvedge Denim Jacket</div>
 <div class="text-secondary">Indigo Washed • Size L</div>
 </div>
 </div>
 <div class="fw-bold text-dark small">৳3,450.00</div>
 </div>

 <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white">
 <div class="d-flex align-items-center gap-2.5">
 <div class="deen-avatar-md deen-avatar-rounded">
 <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=100&auto=format&fit=crop" loading="lazy" alt="Structured Oxford Shirt" class="w-100 h-100 object-fit-cover rounded-2">
 </div>
 <div>
 <div class="fw-bold small text-dark">Structured Oxford Shirt</div>
 <div class="text-secondary">Classic White • Size M</div>
 </div>
 </div>
 <div class="fw-bold text-dark small">৳1,850.00</div>
 </div>

 <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border bg-white">
 <div class="d-flex align-items-center gap-2.5">
 <div class="deen-avatar-md deen-avatar-rounded">
 <img src="https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg" loading="lazy" alt="13.5oz Washed Slim Jeans" class="w-100 h-100 object-fit-cover rounded-2">
 </div>
 <div>
 <div class="fw-bold small text-dark">13.5oz Washed Slim Jeans</div>
 <div class="text-secondary">Vintage Washed • W32</div>
 </div>
 </div>
 <div class="fw-bold text-dark small">৳2,250.00</div>
 </div>
 </div>
 </div>

 <div class="pt-3 border-top">
 <div class="d-flex align-items-baseline justify-content-between mb-3">
 <div>
 <span class="text-secondary small">Bundle Total</span>
 <div class="small text-decoration-line-through text-muted">Total: ৳7,550.00</div>
 </div>
 <div class="text-end">
 <span class="deen-pastel-pill sand mb-1">৳500 Bundle Saving</span>
 <div class="fs-4 fw-bold text-dark font-display">৳7,050.00</div>
 </div>
 </div>
 <button type="button" class="btn-deen-primary w-100 justify-content-center py-3" onclick="addOutfitBundleToCart()">
 <i class="fas fa-shopping-cart me-2"></i> Add Complete Outfit to Cart (৳7,050)
 </button>
 </div>
 </div>
 </div>
 </div>
 </section>

 <!-- Pagination Bar -->
 @if(($totalPages ?? 1) > 1)
 <div class="d-flex justify-content-center align-items-center gap-3 my-5">
 @if($currentPage > 1)
 <a href="{{ route('store.index', ['page' => $currentPage - 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn-deen-outline py-2 px-4">
 <i class="fas fa-arrow-left me-1"></i> Previous
 </a>
 @endif

 <span class="small fw-semibold text-muted px-2">Page {{ $currentPage }} of {{ $totalPages }}</span>

 @if($currentPage < $totalPages)
 <a href="{{ route('store.index', ['page' => $currentPage + 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn-deen-primary py-2 px-4">
 Next <i class="fas fa-arrow-right ms-1"></i>
 </a>
 @endif
 </div>
 @endif

 <!-- Customer Reviews (Pastel Cards with Vast Whitespace) -->
 <section class="deen-section">
 <div class="text-center mb-5">
 <div class="deen-eyebrow justify-content-center">Client Impressions</div>
 <h3 class="fw-semibold text-dark mb-2">Verified Feedback from Across Bangladesh</h3>
 <p class="text-secondary small">Real thoughts from urban fashion and denim enthusiasts</p>
 </div>

 <div class="row g-4">
 <div class="col-md-4">
 <div class="deen-perk-card deen-pastel-linen d-block p-4">
 <div class="d-flex align-items-center gap-3 mb-3">
 <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop" loading="lazy" alt="Nusrat Jahan - Deen Commerce customer">
 <div>
 <div class="fw-semibold text-dark">Nusrat Jahan</div>
 <div class="deen-rating-stars mb-0"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
 </div>
 </div>
 <p class="small text-secondary mb-0">"The 13.5oz Raw Washed Denim Jeans fit impeccably. Delivered in Gulshan within 24 hours. The structure and stitching feel truly luxury."</p>
 </div>
 </div>
 <div class="col-md-4">
 <div class="deen-perk-card deen-pastel-sage d-block p-4">
 <div class="d-flex align-items-center gap-3 mb-3">
 <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop" loading="lazy" alt="Ayman Rahman - Deen Commerce customer">
 <div>
 <div class="fw-semibold text-dark">Ayman Rahman</div>
 <div class="deen-rating-stars mb-0"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
 </div>
 </div>
 <p class="small text-secondary mb-0">"Ordered tailored Oxford Shirts and Polo tees. bKash payment was smooth and product fabric quality exceeded expectations."</p>
 </div>
 </div>
 <div class="col-md-4">
 <div class="deen-perk-card deen-pastel-azure d-block p-4">
 <div class="d-flex align-items-center gap-3 mb-3">
 <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop" loading="lazy" alt="Sabrina Islam - Deen Commerce customer">
 <div>
 <div class="fw-semibold text-dark">Sabrina Islam</div>
 <div class="deen-rating-stars mb-0"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (5.0)</div>
 </div>
 </div>
 <p class="small text-secondary mb-0">"The clean, understated aesthetic of the garments is refreshing. The Steadfast tracking was seamless inside the web experience."</p>
 </div>
 </div>
 </div>
 </section>

 <!-- Curated Lookbook Grid -->
 <section class="deen-section pt-0">
 <div class="d-flex align-items-center justify-content-between mb-4">
 <div>
 <div class="deen-eyebrow">Visual Stories</div>
 <h3 class="fw-semibold text-dark mb-1">@DEENCOMMERCE Studio Lookbook</h3>
 <p class="text-secondary small mb-0">Discover how urban creatives style our garments in Dhaka & beyond</p>
 </div>
 <a href="https://instagram.com" target="_blank" class="btn-deen-outline py-2 px-3">Follow on Instagram &rarr;</a>
 </div>

 <div class="row g-3 g-lg-4">
 <div class="col-6 col-md-3">
 <div class="deen-frame overflow-hidden">
 <img src="https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg" class="w-100 h-100 object-fit-cover" alt="Lookbook 1" loading="lazy">
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame overflow-hidden">
 <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Lookbook 2" loading="lazy">
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame overflow-hidden">
 <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=500&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Lookbook 3" loading="lazy">
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame overflow-hidden">
 <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Lookbook 4" loading="lazy">
 </div>
 </div>
 </div>
 </section>

 <!-- Seasonal Campaign Showcase (Pastel Sage Frame) -->
 <section class="deen-section pt-0">
 <div class="deen-promo-showcase">
 <div class="row align-items-center g-4">
 <div class="col-lg-8">
 <span class="deen-pastel-pill sage mb-3">Limited Capsule Release</span>
 <h2 class="display-6 fw-semibold text-dark mb-2">Refined Urban Apparel for Spring 2026</h2>
 <p class="text-secondary mb-4">
 Experience the intersection of heavy-duty denim durability and modern architectural silhouettes. Designed for everyday comfort and lasting longevity.
 </p>
 <div class="d-flex flex-wrap align-items-center gap-3">
 <a href="#catalog-section" class="btn-deen-primary">
 Explore Capsule Pieces <i class="fas fa-arrow-right ms-2"></i>
 </a>
 <span class="text-secondary small"><i class="fas fa-shield-alt me-1 text-success"></i> 100% Quality Guaranteed</span>
 </div>
 </div>
 </div>
 </div>
 </section>

 </div>
 </main>
@endsection

@push('scripts')
<script>
 /* Homepage-specific: Mobile Filter Drawer Trigger */
 function openMobileFilterDrawer() {
 const modal = document.getElementById('mobileFilterDrawer');
 if (modal) {
 new bootstrap.Modal(modal).show();
 }
 }

 /* Homepage-specific: Add Complete Outfit Bundle to Cart */
 function addOutfitBundleToCart() {
 const outfitItems = [
 { id: 101, name: 'Raw Selvedge Denim Jacket', price: 3200, img: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop', qty: 1, size: 'L' },
 { id: 102, name: 'Structured Oxford Button-Down', price: 1750, img: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop', qty: 1, size: 'M' },
 { id: 103, name: '13.5oz Washed Slim Denim Jeans', price: 2100, img: 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg', qty: 1, size: '32' }
 ];

 let cart = getStoredCart();
 outfitItems.forEach(bundleItem => {
 const existing = cart.find(item => item.id === bundleItem.id && item.size === bundleItem.size);
 if (existing) {
 existing.qty = (existing.qty || 1) + 1;
 } else {
 cart.push(bundleItem);
 }
 });

 localStorage.setItem('deen_cart', JSON.stringify(cart));
 syncCartBadges();
 openMicroCart();
 showCartToast('Complete 3-Piece Outfit (with ৳500 savings)');
 }

 /* Homepage-specific: Instant Fabric Swatch Image Swap */
 function swapCardImage(swatchEl, newImgUrl) {
 if (!swatchEl || !newImgUrl) return;
 const card = swatchEl.closest('.deen-retail-card');
 if (!card) return;

 const mainImg = card.querySelector('.deen-card-main-img');
 if (mainImg) {
 mainImg.style.opacity = '0.5';
 setTimeout(() => {
 mainImg.src = newImgUrl;
 mainImg.style.opacity = '1';
 }, 120);
 }

 const swatches = card.querySelectorAll('.deen-swatch-dot');
 swatches.forEach(s => s.classList.remove('active'));
 swatchEl.classList.add('active');
 }

 document.addEventListener('DOMContentLoaded', () => { // Setup Dynamic Video Card Hover & Autoplay on Viewport
 document.querySelectorAll('.deen-card-video-box').forEach(box => {
 const vid = box.querySelector('video');
 if (vid) {
 box.addEventListener('mouseenter', () => {
 vid.play().catch(() => {});
 });
 box.addEventListener('mouseleave', () => {
 vid.pause();
 vid.currentTime = 0;
 });
 }
 });
 });
</script>
@endpush
