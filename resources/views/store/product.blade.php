@extends('layouts.app')

@section('content')
@php
 $mainImg = $product['images'][0]['src'] ?? null;
 $price = (float)($product['price'] ?? 0);
 $regularPrice = isset($product['regular_price']) ? (float)$product['regular_price'] : null;

 $discountPercent = 0;
 if ($regularPrice && $regularPrice > $price) {
 $discountPercent = round((($regularPrice - $price) / $regularPrice) * 100);
 }
@endphp

<div class="deen-section-py-lg">
 <div class="container">
 <!-- Toast Notification Alert -->
 <div id="cartToast" class="alert de-toast position-fixed top-0 end-0 m-4 rounded-4 shadow-lg border d-none">
 <div class="d-flex align-items-center gap-3">
 <span class="material-symbols-outlined fs-2 text-success">check_circle</span>
 <div>
 <div class="fw-semibold text-dark small" id="toastMessage">Item added to cart.</div>
 <div class="small text-secondary">Proceed to checkout or continue exploring.</div>
 </div>
 <button type="button" class="btn-close ms-auto" onclick="document.getElementById('cartToast').classList.add('d-none')"></button>
 </div>
 </div>

 <!-- Breadcrumb -->
 <nav aria-label="breadcrumb" class="mb-4">
 <ol class="breadcrumb mb-0">
 <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-secondary text-decoration-none small">Home</a></li>
 <li class="breadcrumb-item"><a href="{{ route('store.categories') }}" class="text-secondary text-decoration-none small">Collections</a></li>
 <li class="breadcrumb-item active text-dark small fw-semibold" aria-current="page">{{ Str::limit($product['name'], 32) }}</li>
 </ol>
 </nav>

 <!-- Product Main Showcase -->
 <div class="deen-frame p-4 p-md-5 mb-5" id="mainProductActionArea">
 <div class="row g-5">
 <!-- Gallery Column -->
 <div class="col-lg-6">
 <div class="deen-product-gallery-frame position-relative mb-3">
 @if($mainImg)
 <img src="{{ $mainImg }}" id="mainProductImg" class="w-100 h-100 object-fit-cover" alt="{{ $product['name'] }}">
 @else
 <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
 <span class="material-symbols-outlined fs-1 opacity-40">apparel</span>
 </div>
 @endif

 <!-- Wishlist Button -->
 <button type="button" class="deen-wishlist-btn" data-id="{{ $product['id'] }}" onclick="toggleWishlist({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}', this)" title="Save to Favorites" aria-label="Save to favorites">
 <i class="fas fa-heart"></i>
 </button>

 @if($discountPercent > 0)
 <span class="deen-discount-ribbon">-{{ $discountPercent }}% OFF</span>
 @endif
 </div>

 <!-- Gallery Thumbnails -->
 @if(!empty($product['images']) && count($product['images']) > 1)
 <div class="d-flex gap-2 overflow-auto pb-2">
 @foreach($product['images'] as $img)
 <button type="button" onclick="switchMainImage('{{ $img['src'] }}')" class="rounded-3 border cursor-pointer thumb-img bg-transparent p-0" style="width: 76px; height: 76px;" aria-label="View product image thumbnail"><img src="{{ $img['src'] }}" class="rounded-3" style="width: 76px; height: 76px; object-fit: cover;" alt="Product thumbnail"></button>
 @endforeach
 </div>
 @endif
 </div>

 <!-- Specs & Action Column -->
 <div class="col-lg-6 d-flex flex-column">
 <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
 <span class="deen-vibrant-pill indigo">Catalog ID #{{ $product['id'] }}</span>
 <span class="deen-vibrant-pill emerald">In Stock ({{ $product['stock_quantity'] ?? 'Available' }})</span>
 </div>

 <h1 class="deen-title-lg mb-2"><span class="deen-gradient-text">{{ $product['name'] }}</span></h1>

 <div class="d-flex align-items-center gap-2 mb-4">
 <div class="deen-rating-stars mb-0">
 <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
 </div>
 <span class="fw-bold text-dark small">4.9 / 5.0</span>
 <span class="text-secondary small">(248 Verified Shoppers)</span>
 </div>

 <div class="d-flex align-items-baseline gap-3 mb-3">
 <span class="display-6 fw-bold text-dark font-display">৳{{ number_format($price, 2) }}</span>
 @if($regularPrice && $regularPrice > $price)
 <span class="fs-5 text-muted text-decoration-line-through">৳{{ number_format($regularPrice, 2) }}</span>
 @endif
 </div>

 <!-- Loyalty Coin Pill -->
 <div class="mb-4">
 <button type="button" onclick="openLoyaltyModal()" class="deen-vibrant-pill amber border-0 text-start" title="Click to view VIP rewards">
 <i class="fas fa-coins me-1"></i> Earn {{ round($price / 10) }} Deen Loyalty Coins with this purchase (৳10 = 1 Coin)
 </button>
 </div>

 <p class="text-secondary small mb-4">
 {!! $product['short_description'] ?? 'Crafted with premium heavyweight Bangladeshi denim and refined weave density for timeless comfort.' !!}
 </p>

 <!-- Fabric & Denim Wash Swatches -->
 <div class="mb-3">
 <label class="small fw-semibold text-dark mb-2 d-block">Select Wash & Texture:</label><div class="deen-card-swatches" role="radiogroup" aria-label="Select fabric wash color">
 <button type="button" class="deen-swatch-dot swatch-indigo active" onclick="document.getElementById('mainProductImg').src='{{ $mainImg }}'; this.parentElement.querySelectorAll('.deen-swatch-dot').forEach(s => { s.classList.remove('active'); s.setAttribute('aria-checked', 'false'); }); this.classList.add('active'); this.setAttribute('aria-checked', 'true');" title="Raw Indigo" role="radio" aria-checked="true" aria-label="Raw Indigo"></button>
 <button type="button" class="deen-swatch-dot swatch-vintage" onclick="document.getElementById('mainProductImg').src='https://images.unsplash.com/photo-1542272604-787c3835535d?w=900&auto=format&fit=crop'; this.parentElement.querySelectorAll('.deen-swatch-dot').forEach(s => { s.classList.remove('active'); s.setAttribute('aria-checked', 'false'); }); this.classList.add('active'); this.setAttribute('aria-checked', 'true');" title="Vintage Light Wash" role="radio" aria-checked="false" aria-label="Vintage Light Wash"></button>
 <button type="button" class="deen-swatch-dot swatch-black" onclick="document.getElementById('mainProductImg').src='https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=900&auto=format&fit=crop'; this.parentElement.querySelectorAll('.deen-swatch-dot').forEach(s => { s.classList.remove('active'); s.setAttribute('aria-checked', 'false'); }); this.classList.add('active'); this.setAttribute('aria-checked', 'true');" title="Stonewashed Black" role="radio" aria-checked="false" aria-label="Stonewashed Black"></button>
 <button type="button" class="deen-swatch-dot swatch-ecru" onclick="document.getElementById('mainProductImg').src='https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop'; this.parentElement.querySelectorAll('.deen-swatch-dot').forEach(s => { s.classList.remove('active'); s.setAttribute('aria-checked', 'false'); }); this.classList.add('active'); this.setAttribute('aria-checked', 'true');" title="Raw Ecru" role="radio" aria-checked="false" aria-label="Raw Ecru"></button>
 </div>
 </div>

 <!-- Size Selector Swatches -->
 <div class="mb-4">
 <div class="d-flex justify-content-between align-items-center mb-2">
 <label class="small fw-semibold text-dark mb-0">Select Size: <span id="selectedSizeLabel" class="text-primary fw-bold">M</span></label>
 <div class="d-flex align-items-center gap-3">
 <button type="button" onclick="openSizeCalculator()" class="btn p-0 small text-primary fw-semibold text-decoration-none border-0 bg-transparent">
 <i class="fas fa-ruler-combined me-1"></i> Sizing Calculator
 </button>
 <a href="#sizeGuideModal" data-bs-toggle="modal" class="small text-secondary text-decoration-none"><i class="fas fa-table-list me-1"></i> Size Chart</a>
 </div>
 </div>
 <div class="d-flex flex-wrap gap-2" id="sizeSwatches" role="radiogroup" aria-label="Select garment size"><button type="button" class="deen-size-swatch" onclick="selectSize(this, 'XS')" role="radio" aria-checked="false" aria-label="Size XS">XS</button>
 <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'S')" role="radio" aria-checked="false" aria-label="Size S">S</button>
 <button type="button" class="deen-size-swatch active" onclick="selectSize(this, 'M')" role="radio" aria-checked="true" aria-label="Size M">M</button>
 <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'L')" role="radio" aria-checked="false" aria-label="Size L">L</button>
 <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'XL')" role="radio" aria-checked="false" aria-label="Size XL">XL</button>
 <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'XXL')" role="radio" aria-checked="false" aria-label="Size XXL">XXL</button>
 </div>
 </div>

 <!-- Quantity Stepper & Buttons -->
 <div class="row g-3 align-items-center mb-4">
 <div class="col-4 col-sm-3">
 <div class="input-group">
 <button class="btn btn-outline-secondary btn-sm px-3" onclick="updateQty(-1)" aria-label="Decrease quantity">-</button>
 <input type="text" id="itemQty" class="form-control form-control-sm text-center fw-bold bg-white" value="1" readonly>
 <button class="btn btn-outline-secondary btn-sm px-3" onclick="updateQty(1)" aria-label="Increase quantity">+</button>
 </div>
 </div>
 <div class="col-8 col-sm-9 d-flex gap-2">
 <button onclick="addSingleToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn-deen-vibrant flex-grow-1 justify-content-center py-2.5">
 <i class="fas fa-shopping-cart me-2"></i> Add to Cart
 </button>
 <button onclick="buyNow({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn-deen-orange px-4 py-2.5">
 <i class="fas fa-bolt me-1"></i> Buy Now
 </button>
 </div>
 </div>

 <!-- Social Share -->
 <div class="d-flex align-items-center gap-2 mb-4 pt-2">
 <span class="small text-secondary me-2">Share this piece:</span>
 <a href="https://wa.me/?text={{ urlencode('Check out ' . $product['name'] . ' on Deen Commerce: ' . request()->fullUrl()) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
 <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" aria-label="Share on Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
 <button onclick="navigator.clipboard.writeText(window.location.href); alert('Piece link copied to clipboard.');" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" aria-label="Copy product link to clipboard"><i class="fas fa-link"></i></button>
 </div>

 <!-- Trust Cards Grid -->
 <div class="row g-2 mt-auto">
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-linen p-2 text-center small text-secondary">
 <i class="fas fa-lock text-dark mb-1 d-block"></i> SSL Encrypted
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-sage p-2 text-center small text-secondary">
 <i class="fas fa-rotate-left text-dark mb-1 d-block"></i> 7-Day Returns
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-azure p-2 text-center small text-secondary">
 <i class="fas fa-truck-fast text-dark mb-1 d-block"></i> 24-48h Delivery
 </div>
 </div>
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-sand p-2 text-center small text-secondary">
 <i class="fas fa-headset text-dark mb-1 d-block"></i> 24/7 Concierge
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- ==========================================================================
 HIGH-CONVERTING UX MECHANIC 3: PROGRESSIVE DISCLOSURES
 ========================================================================== -->
 <div class="mb-5">
 <div class="d-flex align-items-center justify-content-between mb-4">
 <div>
 <span class="deen-pastel-pill linen mb-2">Technical Architecture</span>
 <h2 class="deen-title-md mb-0">Garment Blueprint & Specifications</h2>
 </div>
 <button type="button" data-bs-toggle="modal" data-bs-target="#reviewModal" class="btn-deen-outline btn-sm">
 <i class="fas fa-edit me-1"></i> Write Review
 </button>
 </div>

 <!-- Disclosure 1: Architectural Tailoring & Design Story -->
 <div class="deen-disclosure-card expanded" id="disclosureOverview">
 <button type="button" class="deen-disclosure-header" onclick="toggleDisclosure('disclosureOverview')" aria-expanded="true" aria-controls="disclosureOverview-body">
 <div class="d-flex align-items-center gap-2">
 <span class="material-symbols-outlined text-primary fs-5">design_services</span>
 <span>Design Philosophy & Structural Overview</span>
 </div>
 <div class="deen-disclosure-chevron">
 <span class="material-symbols-outlined fs-6">expand_more</span>
 </div>
 </button>
 <div class="deen-disclosure-body">
 <p class="text-secondary mb-0 pt-3">
 {!! $product['description'] ?? 'Engineered for modern urban movement, this garment blends high-density woven cotton with reinforced lock-stitching. Tailored with architectural precision to maintain silhouette integrity and breathability through intensive wear cycles.' !!}
 </p>
 </div>
 </div>

 <!-- Disclosure 2: Technical Specifications & Full Spec Sheet -->
 <div class="deen-disclosure-card expanded" id="disclosureSpecs">
 <button type="button" class="deen-disclosure-header" onclick="toggleDisclosure('disclosureSpecs')" aria-expanded="true" aria-controls="disclosureSpecs-body">
 <div class="d-flex align-items-center gap-2">
 <span class="material-symbols-outlined text-primary fs-5">straighten</span>
 <span>Fabric Blueprint & Technical Material Sheet</span>
 </div>
 <div class="deen-disclosure-chevron">
 <span class="material-symbols-outlined fs-6">expand_more</span>
 </div>
 </button>
 <div class="deen-disclosure-body pt-3">
 <div class="deen-spec-sheet-grid mb-3">
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Material Composition</div>
 <div class="deen-spec-sheet-val">98% Combed Cotton, 2% Elastane</div>
 </div>
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Fabric Weight</div>
 <div class="deen-spec-sheet-val">13.5 oz Raw Heavy Denim</div>
 </div>
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Weave Structure</div>
 <div class="deen-spec-sheet-val">3x1 Right Hand Twill</div>
 </div>
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Stitch Architecture</div>
 <div class="deen-spec-sheet-val">Reinforced Core-Spun Lockstitch</div>
 </div>
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Dyeing & Finish</div>
 <div class="deen-spec-sheet-val">Rope Dyed Indigo, Zero Bleed Cured</div>
 </div>
 <div class="deen-spec-sheet-item">
 <div class="deen-spec-sheet-label">Care Recommendations</div>
 <div class="deen-spec-sheet-val">Cold Wash (30°C), Line Dry in Shade</div>
 </div>
 </div>
 </div>
 </div>

 <!-- Disclosure 3: Nationwide Delivery & Exchange Terms -->
 <div class="deen-disclosure-card" id="disclosureShipping">
 <button type="button" class="deen-disclosure-header" onclick="toggleDisclosure('disclosureShipping')" aria-expanded="false" aria-controls="disclosureShipping-body">
 <div class="d-flex align-items-center gap-2">
 <span class="material-symbols-outlined text-primary fs-5">local_shipping</span>
 <span>Nationwide Dispatch & 7-Day Exchange Policy</span>
 </div>
 <div class="deen-disclosure-chevron">
 <span class="material-symbols-outlined fs-6">expand_more</span>
 </div>
 </button>
 <div class="deen-disclosure-body pt-3">
 <div class="row g-3">
 <div class="col-md-6">
 <div class="p-3 deen-frame deen-pastel-linen h-100">
 <h4 class="deen-title-sm mb-2"><i class="fas fa-truck-fast text-dark me-2"></i> Delivery Speed</h4>
 <ul class="text-secondary small mb-0 d-flex flex-column gap-1">
 <li><strong>Dhaka Metropolitan:</strong> 24–48 hours (৳70)</li>
 <li><strong>Nationwide Districts:</strong> 2–4 days (৳130)</li>
 <li><strong>Orders over ৳2,000:</strong> 100% Free Shipping</li>
 </ul>
 </div>
 </div>
 <div class="col-md-6">
 <div class="p-3 deen-frame deen-pastel-sage h-100">
 <h4 class="deen-title-sm mb-2"><i class="fas fa-rotate-left text-dark me-2"></i> 7-Day Exchange</h4>
 <p class="text-secondary small mb-0">
 Try on at home with total peace of mind. Any unworn piece with original tags can be exchanged within 7 days via our courier pickup service.
 </p>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- "YOU MIGHT LIKE" RECOMMENDATIONS GRID WITH DYNAMIC VIDEO CARDS -->
 <div class="my-5">
 <div class="d-flex align-items-center justify-content-between mb-4">
 <div>
 <span class="deen-pastel-pill lavender mb-2">Curated Complements</span>
 <h2 class="deen-title-md mb-0">Recommended Pieces</h2>
 </div>
 <a href="{{ route('store.categories') }}" class="btn-deen-outline btn-sm">Explore All &rarr;</a>
 </div>

 @if(!empty($relatedProducts))
 <div class="row g-3 g-md-4">
 @foreach($relatedProducts as $rel)
 @php
 $relImg = $rel['images'][0]['src'] ?? null;
 $relPrice = (float)($rel['price'] ?? 0);
 @endphp
 <div class="col-6 col-md-3">
 <div class="deen-retail-card position-relative">
 <div class="deen-retail-img-box deen-card-video-box" style="height: 220px;">
 @if($relImg)
 <img src="{{ $relImg }}" class="deen-retail-img" alt="{{ $rel['name'] }}" loading="lazy">
 @else
 <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-secondary">
 <span class="material-symbols-outlined fs-1 opacity-40">apparel</span>
 </div>
 @endif

 <!-- Subtle Movement Video Loop -->
 <video class="deen-card-video" muted loop playsinline preload="none" poster="{{ $relImg }}">
 <source src="https://assets.mixkit.co/videos/preview/mixkit-fashion-model-in-a-denim-jacket-and-pants-40763-small.mp4" type="video/mp4">
 </video>
 <span class="deen-video-badge"><i class="fas fa-play"></i> Motion</span>

 <button type="button" class="deen-wishlist-btn" data-id="{{ $rel['id'] }}" onclick="toggleWishlist({{ $rel['id'] }}, '{{ addslashes($rel['name']) }}', {{ $relPrice }}, '{{ addslashes($relImg) }}', this)">
 <i class="fas fa-heart"></i>
 </button>
 </div>
 <div class="deen-retail-body">
 <h3 class="deen-retail-title text-truncate" title="{{ $rel['name'] }}">{{ $rel['name'] }}</h3>
 <div class="deen-retail-price-row mb-3">
 <span class="deen-retail-price">৳{{ number_format($relPrice, 2) }}</span>
 </div>
 <a href="{{ route('store.product.detail', $rel['id']) }}" class="btn-deen-primary w-100 text-center justify-content-center mt-auto">
 View Piece
 </a>
 </div>
 </div>
 </div>
 @endforeach
 </div>
 @endif
 </div>
 </div>
</div>

<!-- ==========================================================================
 HIGH-CONVERTING UX MECHANIC 2: ONE-TAP STICKY BUY FOOTER
 ========================================================================== -->
<div class="deen-sticky-buy-footer" id="stickyBuyFooter">
 <div class="container d-flex align-items-center justify-content-between gap-2 gap-md-4">
 <div class="d-flex align-items-center gap-3 overflow-hidden">
 @if($mainImg)
 <img src="{{ $mainImg }}" loading="lazy" class="deen-sticky-thumb d-none d-sm-block" alt="{{ $product['name'] }}">
 @endif
 <div class="overflow-hidden">
 <div class="fw-bold text-dark small text-truncate">{{ $product['name'] }}</div>
 <div class="d-flex align-items-center gap-2">
 <span class="fw-bold text-dark font-display">৳{{ number_format($price, 2) }}</span>
 @if($regularPrice && $regularPrice > $price)
 <span class="small text-muted text-decoration-line-through">৳{{ number_format($regularPrice, 2) }}</span>
 @endif
 <span class="deen-pastel-pill azure py-0 px-2 small" id="stickySizeBadge">Size M</span>
 </div>
 </div>
 </div>

 <div class="d-flex align-items-center gap-2 ms-auto">
 <button onclick="addSingleToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn-deen-outline btn-sm px-3 d-none d-md-inline-flex">
 <i class="fas fa-shopping-cart me-1"></i> Add to Cart
 </button>
 <button onclick="buyNow({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn-deen-primary btn-sm px-4">
 <span>Buy Now</span>
 <i class="fas fa-bolt ms-1"></i>
 </button>
 </div>
 </div>
</div>

<!-- Customer Review Submission Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered">
 <div class="modal-content deen-frame p-4">
 <div class="modal-header border-0 pb-2">
 <div>
 <span class="deen-pastel-pill sage mb-1">Feedback</span>
 <h3 class="deen-title-sm mb-0">Write a Verified Review</h3>
 </div>
 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body py-3">
 <form id="reviewForm" onsubmit="event.preventDefault(); alert('Thank you. Your review has been submitted for editorial moderation.'); bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();">
 <div class="mb-3">
 <label class="form-label small fw-semibold text-secondary">Rating</label>
 <div class="d-flex gap-2 text-warning fs-5">
 <i class="fas fa-star cursor-pointer"></i>
 <i class="fas fa-star cursor-pointer"></i>
 <i class="fas fa-star cursor-pointer"></i>
 <i class="fas fa-star cursor-pointer"></i>
 <i class="fas fa-star cursor-pointer"></i>
 </div>
 </div>
 <div class="mb-3">
 <label class="form-label small fw-semibold text-secondary">Your Name *</label>
 <input type="text" class="form-control deen-input" placeholder="e.g. Sajid Ahmed" required>
 </div>
 <div class="mb-3">
 <label class="form-label small fw-semibold text-secondary">Review Notes *</label>
 <textarea class="form-control deen-input" rows="4" placeholder="Comfortable denim stretch fit, premium stitch quality..." required></textarea>
 </div>
 <button type="submit" class="btn-deen-primary w-100 justify-content-center py-2">Submit Review</button>
 </form>
 </div>
 </div>
 </div>
</div>

<script>
let currentSelectedSize = 'M';

function selectSize(element, size) {
 document.querySelectorAll('#sizeSwatches .deen-size-swatch').forEach(btn => btn.classList.remove('active'));
 element.classList.add('active');
 currentSelectedSize = size;
 const lbl = document.getElementById('selectedSizeLabel');
 if (lbl) lbl.innerText = size;
 const stickyBadge = document.getElementById('stickySizeBadge');
 if (stickyBadge) stickyBadge.innerText = 'Size ' + size;
}

function updateQty(change) {
 const qtyInput = document.getElementById('itemQty');
 let val = parseInt(qtyInput.value) || 1;
 val += change;
 if (val < 1) val = 1;
 qtyInput.value = val;
}

function switchMainImage(src) {
 document.getElementById('mainProductImg').src = src;
}

function toggleDisclosure(id) {
 const card = document.getElementById(id);
 if (card) {
 card.classList.toggle('expanded');
 }
}

function addSingleToCart(id, name, price, img) {
 const qty = parseInt(document.getElementById('itemQty').value) || 1;
 const sizeName = name + ' (Size: ' + currentSelectedSize + ')';
 
 let cart = getStoredCart();
 const existing = cart.find(item => item.id === id && item.size === currentSelectedSize);
 
 if (existing) {
 existing.qty += qty;
 } else {
 cart.push({ id, name: sizeName, price, img, qty, size: currentSelectedSize });
 }
 
 localStorage.setItem('deen_cart', JSON.stringify(cart));
 if (typeof syncCartBadges === 'function') {
 syncCartBadges();
 }

 // High-Converting Sticky Micro-Cart Auto-Open Trigger
 if (typeof openMicroCart === 'function') {
 openMicroCart();
 }
}

function buyNow(id, name, price, img) {
 addSingleToCart(id, name, price, img);
 window.location.href = "{{ route('store.checkout') }}";
}

/* HIGH-CONVERTING UX MECHANIC 2: INTERSECTION OBSERVER FOR ONE-TAP STICKY FOOTER */
document.addEventListener('DOMContentLoaded', () => {
 const mainActionArea = document.getElementById('mainProductActionArea');
 const stickyFooter = document.getElementById('stickyBuyFooter');

 if (mainActionArea && stickyFooter) {
 const observer = new IntersectionObserver((entries) => {
 entries.forEach(entry => {
 // When main buy section is scrolled out of view, show sticky footer
 if (!entry.isIntersecting) {
 stickyFooter.classList.add('visible');
 } else {
 stickyFooter.classList.remove('visible');
 }
 });
 }, { threshold: 0.1 });

 observer.observe(mainActionArea);
 }

 // Setup Video Card Hover & Autoplay on Viewport for recommendation cards
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

 // Auto-select calculated AI size recommendation if stored
 const savedRecSize = localStorage.getItem('deen_recommended_size');
 if (savedRecSize) {
 document.querySelectorAll('#sizeSwatches .deen-size-swatch').forEach(btn => {
 if (btn.innerText.trim() === savedRecSize) {
 selectSize(btn, savedRecSize);
 }
 });
 }

 if (typeof trackRecentlyViewed === 'function') {
 trackRecentlyViewed({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}');
 }
});
</script>
@endsection

