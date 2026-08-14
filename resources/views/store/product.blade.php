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

<div class="py-5 bg-light">
    <div class="container">
        <!-- Toast Notification Alert -->
        <div id="cartToast" class="alert alert-dark position-fixed top-0 end-0 m-4 rounded-4 shadow-lg border-2 border-primary d-none" style="z-index: 1050; max-width: 380px;">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-check-circle text-success fs-3"></i>
                <div>
                    <div class="fw-bold text-white small" id="toastMessage">Item added to shopping bag!</div>
                    <div class="small text-white-50">Proceed to checkout or continue shopping.</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" onclick="document.getElementById('cartToast').classList.add('d-none')"></button>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('store.categories') }}" class="text-decoration-none text-muted">Fashion Catalog</a></li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ Str::limit($product['name'], 30) }}</li>
            </ol>
        </nav>

        <!-- Product Main Showcase -->
        <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-5">
            <div class="row g-5">
                <!-- Gallery Column -->
                <div class="col-lg-6">
                    <div class="position-relative bg-light rounded-4 overflow-hidden mb-3 border" style="height: 420px;">
                        @if($mainImg)
                            <img src="{{ $mainImg }}" id="mainProductImg" class="w-100 h-100 object-fit-cover transition-img" alt="{{ $product['name'] }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-tshirt fa-6x text-secondary opacity-40"></i>
                            </div>
                        @endif

                        <!-- Wishlist Heart Button -->
                        <button type="button" class="deen-wishlist-btn" data-id="{{ $product['id'] }}" onclick="toggleWishlist({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}', this)" title="Save to Favorites">
                            <i class="fas fa-heart"></i>
                        </button>

                        @if($discountPercent > 0)
                            <span class="deen-discount-ribbon font-monospace">-{{ $discountPercent }}% OFF</span>
                        @endif
                    </div>

                    <!-- Gallery Thumbnails -->
                    @if(!empty($product['images']) && count($product['images']) > 1)
                        <div class="d-flex gap-2 overflow-auto">
                            @foreach($product['images'] as $img)
                                <img src="{{ $img['src'] }}" onclick="switchMainImage(this.src)" class="rounded-3 border border-2 cursor-pointer thumb-img" style="width: 80px; height: 80px; object-fit: cover;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Specs & Action Column -->
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-dark fw-bold">Woo Item #{{ $product['id'] }}</span>
                        <span class="badge bg-success">In Stock ({{ $product['stock_quantity'] ?? 'Available' }})</span>
                        <span class="deen-urgency-badge"><i class="fas fa-bolt"></i> Only 3 left in stock!</span>
                    </div>

                    <h2 class="fw-bold text-dark mb-2">{{ $product['name'] }}</h2>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="fw-bold text-dark">4.9</span>
                        <span class="text-muted small">(248 Verified Reviews)</span>
                    </div>

                    <div class="d-flex align-items-baseline gap-3 mb-2">
                        <span class="display-5 fw-bold text-dark">৳{{ number_format($price, 2) }}</span>
                        @if($regularPrice && $regularPrice > $price)
                            <span class="fs-4 text-muted text-decoration-line-through">৳{{ number_format($regularPrice, 2) }}</span>
                        @endif
                    </div>

                    <!-- Deen VIP Loyalty Coins Earning Badge -->
                    <div class="mb-4">
                        <button type="button" onclick="openLoyaltyModal()" class="deen-loyalty-badge px-3 py-2 text-dark font-monospace" title="Click to view VIP rewards">
                            <i class="fas fa-coins me-1"></i> Earn {{ round($price / 10) }} Deen Coins on this item! (৳10 = 1 Coin)
                        </button>
                    </div>

                    <p class="text-muted mb-4">{!! $product['short_description'] ?? $product['description'] ?? 'Premium quality Bangladeshi urban apparel with comfort stretch fabric.' !!}</p>

                    <!-- Size Selector Swatches Grid -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold text-dark mb-0">Select Waist / Apparel Size: <span id="selectedSizeLabel" class="text-primary">32</span></label>
                            <a href="#sizeGuideModal" data-bs-toggle="modal" class="small text-muted text-decoration-underline">Size Guide</a>
                        </div>
                        <div class="deen-size-grid" id="sizeSwatches">
                            <button type="button" class="deen-size-grid-btn" onclick="selectSize(this, 'XS')">XS</button>
                            <button type="button" class="deen-size-grid-btn" onclick="selectSize(this, 'S')">S</button>
                            <button type="button" class="deen-size-grid-btn active" onclick="selectSize(this, 'M')">M</button>
                            <button type="button" class="deen-size-grid-btn" onclick="selectSize(this, 'L')">L</button>
                            <button type="button" class="deen-size-grid-btn" onclick="selectSize(this, 'XL')">XL</button>
                            <button type="button" class="deen-size-grid-btn" onclick="selectSize(this, 'XXL')">XXL</button>
                        </div>
                    </div>

                    <!-- Quantity Counter & Action Buttons (Min 48px touch targets) -->
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-4 col-sm-3">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary fw-bold" onclick="updateQty(-1)">-</button>
                                <input type="text" id="itemQty" class="form-control text-center fw-bold" value="1" readonly>
                                <button class="btn btn-outline-secondary fw-bold" onclick="updateQty(1)">+</button>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9 d-flex gap-2">
                            <button onclick="addSingleToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn btn-dark deen-btn-touch rounded-pill flex-grow-1 shadow-sm">
                                <i class="fas fa-shopping-bag me-2"></i> Add to Bag
                            </button>
                            <button onclick="buyNow({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn btn-danger deen-btn-touch rounded-pill px-4 shadow">
                                Buy Now
                            </button>
                        </div>
                    </div>

                    <!-- Social Product Share Bar -->
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="small fw-bold text-muted me-1">Share this item:</span>
                        <a href="https://wa.me/?text={{ urlencode('Check out ' . $product['name'] . ' on Deen Commerce: ' . request()->fullUrl()) }}" target="_blank" class="deen-share-btn wa" title="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="deen-share-btn fb" title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Product link copied to clipboard!');" class="deen-share-btn copy" title="Copy Product Link">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>

                    <!-- Trust Signals Bar -->
                    <div class="p-3 rounded-4 bg-light border">
                        <div class="row g-2 text-center text-muted small fw-bold">
                            <div class="col-6 col-md-3"><i class="fas fa-lock text-success me-1"></i> Secure 256-Bit SSL</div>
                            <div class="col-6 col-md-3"><i class="fas fa-rotate-left text-primary me-1"></i> 30-Day Returns</div>
                            <div class="col-6 col-md-3"><i class="fas fa-headset text-warning me-1"></i> 24/7 Support</div>
                            <div class="col-6 col-md-3"><i class="fas fa-truck-fast text-info me-1"></i> Fast 24-48h BD</div>
                        </div>
                    </div>

                    <!-- Retail Perks Bar -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-2 text-center text-md-start small text-muted">
                            <div class="col-6"><i class="fas fa-truck-fast text-primary me-1"></i> Free Shipping Over ৳2,000</div>
                            <div class="col-6"><i class="fas fa-rotate-left text-success me-1"></i> 7 Days Easy Returns</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPECIFICATIONS & DESCRIPTION TABS -->
        <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-5">
            <ul class="nav nav-tabs border-bottom mb-4" id="productDetailTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold text-dark" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button"><i class="fas fa-align-left me-2"></i> Description</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold text-dark" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button"><i class="fas fa-sliders me-2"></i> Specifications & Care</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold text-dark" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping-pane" type="button"><i class="fas fa-truck me-2"></i> Shipping & Returns</button>
                </li>
                <li class="nav-item ms-auto">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#reviewModal" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-3">
                        <i class="fas fa-edit me-1"></i> Write a Review
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="productDetailTabContent">
                <!-- Tab 1: Description -->
                <div class="tab-pane fade show active" id="desc-pane">
                    <h5 class="fw-bold text-dark mb-3">Product Overview & Design Story</h5>
                    <p class="text-muted">{!! $product['description'] ?? 'Designed for modern urban comfort, this garment features high-density cotton weave with precision stitching. Engineered to retain shape and comfort through daily wear.' !!}</p>
                </div>

                <!-- Tab 2: Specs -->
                <div class="tab-pane fade" id="specs-pane">
                    <h5 class="fw-bold text-dark mb-3">Fabric & Care Specifications</h5>
                    <table class="table table-bordered align-middle" style="max-width: 600px;">
                        <tbody>
                            <tr><th class="bg-light" style="width: 200px;">Material Composition</th><td>98% Premium Cotton, 2% Elastane Stretch</td></tr>
                            <tr><th class="bg-light">Fit Type</th><td>Slim Fit Through Hip & Thigh</td></tr>
                            <tr><th class="bg-light">Wash Type</th><td>Custom Raw Washed Denim / Enzymatic Softener</td></tr>
                            <tr><th class="bg-light">Care Instructions</th><td>Machine Wash Cold, Tumble Dry Low, Do Not Bleach</td></tr>
                            <tr><th class="bg-light">Country of Origin</th><td>Made in Bangladesh (Deen Apparel Division)</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab 3: Shipping -->
                <div class="tab-pane fade" id="shipping-pane">
                    <h5 class="fw-bold text-dark mb-3">Fast Nationwide Shipping & Easy Returns</h5>
                    <ul class="text-muted">
                        <li><strong>Standard Delivery:</strong> 2-3 business days within Dhaka Metropolitan area, 3-5 days across all Bangladesh districts via Steadfast Courier & Pathao.</li>
                        <li><strong>Free Shipping:</strong> Automatically applied to all orders above ৳2,000.</li>
                        <li><strong>7-Day Returns:</strong> Exchange or return unworn items with original tags intact within 7 days of delivery.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- "YOU MIGHT LIKE" RECOMMENDATIONS GRID -->
        <div class="my-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fas fa-sparkles text-warning me-2"></i> You Might Also Like</h4>
                    <p class="text-muted small mb-0">Recommended apparel curated based on your fashion choices</p>
                </div>
                <a href="{{ route('store.categories') }}" class="btn btn-sm btn-outline-dark rounded-pill fw-bold">Explore All &rarr;</a>
            </div>

            @if(!empty($relatedProducts))
                <div class="row g-3 g-md-4">
                    @foreach($relatedProducts as $rel)
                        @php
                            $relImg = $rel['images'][0]['src'] ?? null;
                            $relPrice = (float)($rel['price'] ?? 0);
                        @endphp
                        <div class="col-6 col-md-3">
                            <div class="deen-retail-card h-100">
                                <div class="deen-retail-img-box" style="height: 180px;">
                                    @if($relImg)
                                        <img src="{{ $relImg }}" class="deen-retail-img" alt="{{ $rel['name'] }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-tshirt fa-3x text-secondary opacity-40"></i>
                                        </div>
                                    @endif
                                    <button type="button" class="deen-wishlist-btn" data-id="{{ $rel['id'] }}" onclick="toggleWishlist({{ $rel['id'] }}, '{{ addslashes($rel['name']) }}', {{ $relPrice }}, '{{ addslashes($relImg) }}', this)">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="deen-retail-body p-3">
                                    <h6 class="deen-retail-title text-truncate" title="{{ $rel['name'] }}">{{ $rel['name'] }}</h6>
                                    <div class="deen-retail-price-row mb-2">
                                        <span class="deen-retail-price">৳{{ number_format($relPrice, 2) }}</span>
                                    </div>
                                    <a href="{{ route('store.product.detail', $rel['id']) }}" class="btn btn-sm btn-dark rounded-pill w-100 mt-auto fw-bold">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- INSTAGRAM SHOPPABLE LOOKBOOK FEED -->
        <div class="my-5 pt-3 border-top">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fab fa-instagram text-danger me-2"></i> Styled by Real Shoppers (@DEENCOMMERCE)</h5>
                    <p class="text-muted small mb-0">See how our fashion community styles this collection</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="deen-insta-card">
                        <img src="https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg" class="deen-insta-img" alt="Shopper 1">
                        <div class="deen-insta-overlay">
                            <div class="small text-white fw-bold">Washed Fit • Dhaka</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="deen-insta-card">
                        <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Shopper 2">
                        <div class="deen-insta-overlay">
                            <div class="small text-white fw-bold">Urban Slim Fit • Chittagong</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="deen-insta-card">
                        <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Shopper 3">
                        <div class="deen-insta-overlay">
                            <div class="small text-white fw-bold">Street Casual • Sylhet</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="deen-insta-card">
                        <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop" class="deen-insta-img" alt="Shopper 4">
                        <div class="deen-insta-overlay">
                            <div class="small text-white fw-bold">Authentic Denim • Uttara</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
let currentSelectedSize = '32';

function selectSize(element, size) {
    document.querySelectorAll('#sizeSwatches .deen-size-swatch').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    currentSelectedSize = size;
    document.getElementById('selectedSizeLabel').innerText = size;
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
    
    const toast = document.getElementById('cartToast');
    if (toast) {
        document.getElementById('toastMessage').innerText = name + ' (Size ' + currentSelectedSize + ' x ' + qty + ') added to bag!';
        toast.classList.remove('d-none');
        setTimeout(() => {
            toast.classList.add('d-none');
        }, 4000);
    }
}

function buyNow(id, name, price, img) {
    addSingleToCart(id, name, price, img);
    window.location.href = "{{ route('store.checkout') }}";
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof trackRecentlyViewed === 'function') {
        trackRecentlyViewed({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}');
    }
});
</script>
    <!-- BANGLADESHI APPAREL SIZE GUIDE MODAL -->
    <div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white"><i class="fas fa-ruler-combined me-2 text-warning"></i> Deen Denim & Apparel Size Measurement Chart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-white-50 small mb-3">All measurements are provided in inches and centimeters for standard Bangladeshi retail fit.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped text-center deen-size-table rounded-3 overflow-hidden">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Waist (Inches)</th>
                                    <th>Hips (Inches)</th>
                                    <th>Thigh (Inches)</th>
                                    <th>Length (Inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><strong class="text-warning">XS (28)</strong></td><td>28" (71cm)</td><td>36" (91cm)</td><td>22" (56cm)</td><td>39" (99cm)</td></tr>
                                <tr><td><strong class="text-warning">S (30)</strong></td><td>30" (76cm)</td><td>38" (96cm)</td><td>23" (58cm)</td><td>40" (101cm)</td></tr>
                                <tr class="table-active"><td><strong class="text-warning">M (32)</strong></td><td>32" (81cm)</td><td>40" (101cm)</td><td>24" (61cm)</td><td>41" (104cm)</td></tr>
                                <tr><td><strong class="text-warning">L (34)</strong></td><td>34" (86cm)</td><td>42" (106cm)</td><td>25" (63cm)</td><td>41.5" (105cm)</td></tr>
                                <tr><td><strong class="text-warning">XL (36)</strong></td><td>36" (91cm)</td><td>44" (111cm)</td><td>26" (66cm)</td><td>42" (106cm)</td></tr>
                                <tr><td><strong class="text-warning">XXL (38)</strong></td><td>38" (96cm)</td><td>46" (116cm)</td><td>27" (68cm)</td><td>42.5" (108cm)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light rounded-pill" data-bs-dismiss="modal">Close Size Guide</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CUSTOMER REVIEW SUBMISSION MODAL -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white"><i class="fas fa-star me-2 text-warning"></i> Write a Customer Review</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="reviewForm" onsubmit="event.preventDefault(); alert('Thank you! Your verified review has been submitted for approval.'); bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();">
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">Select Rating</label>
                            <div class="deen-star-selector">
                                <i class="fas fa-star active" onclick="setReviewRating(1)"></i>
                                <i class="fas fa-star active" onclick="setReviewRating(2)"></i>
                                <i class="fas fa-star active" onclick="setReviewRating(3)"></i>
                                <i class="fas fa-star active" onclick="setReviewRating(4)"></i>
                                <i class="fas fa-star active" onclick="setReviewRating(5)"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">Your Name *</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="Sajid Ahmed" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">Your Review Comments *</label>
                            <textarea class="form-control bg-dark text-white border-secondary rounded-3" rows="4" placeholder="Comfortable denim stretch fit, premium stitch quality!" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 rounded-pill font-monospace fw-bold text-dark py-2">Submit Verified Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
