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
                        <span class="badge bg-secondary font-monospace">SKU: {{ $product['sku'] ?? 'DN-APRL-001' }}</span>
                    </div>

                    <h2 class="fw-bold text-dark mb-3">{{ $product['name'] }}</h2>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="fw-bold text-dark">4.9</span>
                        <span class="text-muted small">(128 Customer Reviews)</span>
                    </div>

                    <div class="d-flex align-items-baseline gap-3 mb-4">
                        <span class="display-5 fw-bold text-dark">৳{{ number_format($price, 2) }}</span>
                        @if($regularPrice && $regularPrice > $price)
                            <span class="fs-4 text-muted text-decoration-line-through">৳{{ number_format($regularPrice, 2) }}</span>
                        @endif
                    </div>

                    <p class="text-muted mb-4">{!! $product['short_description'] ?? $product['description'] ?? 'Premium quality Bangladeshi urban apparel with comfort stretch fabric.' !!}</p>

                    <!-- Size Selector Swatches -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold text-dark mb-0">Select Waist / Apparel Size: <span id="selectedSizeLabel" class="text-primary">32</span></label>
                            <a href="#sizeGuideModal" data-bs-toggle="modal" class="small text-muted text-decoration-underline">Size Guide</a>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="sizeSwatches">
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, '28')">28</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, '30')">30</button>
                            <button type="button" class="deen-size-swatch active" onclick="selectSize(this, '32')">32</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, '34')">34</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, '36')">36</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, '38')">38</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'M')">M</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'L')">L</button>
                            <button type="button" class="deen-size-swatch" onclick="selectSize(this, 'XL')">XL</button>
                        </div>
                    </div>

                    <!-- Quantity Counter & Action Buttons -->
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-4 col-sm-3">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary fw-bold" onclick="updateQty(-1)">-</button>
                                <input type="text" id="itemQty" class="form-control text-center fw-bold" value="1" readonly>
                                <button class="btn btn-outline-secondary fw-bold" onclick="updateQty(1)">+</button>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9 d-flex gap-2">
                            <button onclick="addSingleToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn btn-dark btn-lg rounded-pill fw-bold flex-grow-1 shadow-sm">
                                <i class="fas fa-shopping-bag me-2"></i> Add to Bag
                            </button>
                            <button onclick="buyNow({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn btn-danger btn-lg rounded-pill fw-bold px-4 shadow">
                                Buy Now
                            </button>
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

        <!-- Related Apparel Grid -->
        @if(!empty($relatedProducts))
            <div>
                <h4 class="fw-bold text-dark mb-4"><i class="fas fa-tshirt text-primary me-2"></i> You Might Also Like</h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $rel)
                        @php
                            $relImg = $rel['images'][0]['src'] ?? null;
                            $relPrice = (float)($rel['price'] ?? 0);
                        @endphp
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden text-decoration-none">
                                <div style="height: 180px;" class="bg-light">
                                    @if($relImg)
                                        <img src="{{ $relImg }}" class="w-100 h-100 object-fit-cover" alt="{{ $rel['name'] }}">
                                    @endif
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $rel['name'] }}</h6>
                                    <div class="fw-bold text-primary mb-2">৳{{ number_format($relPrice, 2) }}</div>
                                    <a href="{{ route('store.product.detail', $rel['id']) }}" class="btn btn-sm btn-outline-dark rounded-pill w-100 mt-auto">View Product</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
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
    
    let cart = JSON.parse(localStorage.getItem('deen_cart') || '[]');
    const existing = cart.find(item => item.id === id && item.size === currentSelectedSize);
    
    if (existing) {
        existing.qty += qty;
    } else {
        cart.push({ id, name: sizeName, price, img, qty, size: currentSelectedSize });
    }
    
    localStorage.setItem('deen_cart', JSON.stringify(cart));
    
    const toast = document.getElementById('cartToast');
    document.getElementById('toastMessage').innerText = name + ' (Size ' + currentSelectedSize + ' x ' + qty + ') added to bag!';
    toast.classList.remove('d-none');
    
    setTimeout(() => {
        toast.classList.add('d-none');
    }, 4000);
}

function buyNow(id, name, price, img) {
    addSingleToCart(id, name, price, img);
    window.location.href = "{{ route('store.checkout') }}";
}
</script>
@endsection
