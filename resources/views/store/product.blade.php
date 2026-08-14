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
                            <img src="{{ $mainImg }}" id="mainProductImg" class="w-100 h-100 object-fit-cover" alt="{{ $product['name'] }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-tshirt fa-6x text-secondary opacity-40"></i>
                            </div>
                        @endif

                        @if($discountPercent > 0)
                            <span class="deen-discount-ribbon font-monospace">-{{ $discountPercent }}% OFF</span>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if(!empty($product['images']) && count($product['images']) > 1)
                        <div class="d-flex gap-2 overflow-auto">
                            @foreach($product['images'] as $img)
                                <img src="{{ $img['src'] }}" onclick="document.getElementById('mainProductImg').src='{{ $img['src'] }}'" class="rounded-3 border border-2 cursor-pointer" style="width: 80px; height: 80px; object-fit: cover;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Specs & Action Column -->
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-dark fw-bold">Woo Item #{{ $product['id'] }}</span>
                        <span class="badge bg-success">In Stock ({{ $product['stock_quantity'] ?? 'Available' }})</span>
                        <span class="badge bg-secondary font-monospace">SKU: {{ $product['sku'] ?? 'N/A' }}</span>
                    </div>

                    <h2 class="fw-bold text-dark mb-3">{{ $product['name'] }}</h2>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <span class="fw-bold text-dark">5.0</span>
                        <span class="text-muted small">(128 Customer Reviews)</span>
                    </div>

                    <div class="d-flex align-items-baseline gap-3 mb-4">
                        <span class="display-5 fw-bold text-dark">৳{{ number_format($price, 2) }}</span>
                        @if($regularPrice && $regularPrice > $price)
                            <span class="fs-4 text-muted text-decoration-line-through">৳{{ number_format($regularPrice, 2) }}</span>
                        @endif
                    </div>

                    <p class="text-muted mb-4">{!! $product['short_description'] ?? $product['description'] ?? 'Premium quality fashion garment from Deen Commerce.' !!}</p>

                    <!-- Size Selector -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-block">Select Waist / Apparel Size:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="deen-size-swatch active">28</div>
                            <div class="deen-size-swatch">30</div>
                            <div class="deen-size-swatch">32</div>
                            <div class="deen-size-swatch">34</div>
                            <div class="deen-size-swatch">36</div>
                            <div class="deen-size-swatch">38</div>
                        </div>
                    </div>

                    <!-- Quantity & Action Buttons -->
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-4 col-sm-3">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" onclick="let q=document.getElementById('itemQty'); if(q.value>1) q.value--;">-</button>
                                <input type="text" id="itemQty" class="form-control text-center fw-bold" value="1" readonly>
                                <button class="btn btn-outline-secondary" onclick="let q=document.getElementById('itemQty'); q.value++;">+</button>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9 d-flex gap-2">
                            <button onclick="addSingleToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($mainImg) }}')" class="btn btn-dark btn-lg rounded-pill fw-bold flex-grow-1">
                                <i class="fas fa-shopping-bag me-2"></i> Add to Bag
                            </button>
                            <a href="{{ route('store.checkout') }}" class="btn btn-danger btn-lg rounded-pill fw-bold px-4">
                                Checkout
                            </a>
                        </div>
                    </div>

                    <!-- Retail Perks -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-2 text-center text-md-start small text-muted">
                            <div class="col-6"><i class="fas fa-truck-fast text-primary me-1"></i> Free Shipping Over ৳2000</div>
                            <div class="col-6"><i class="fas fa-rotate-left text-success me-1"></i> 7 Days Return Policy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if(!empty($relatedProducts))
            <div class="mt-5">
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
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $rel['name'] }}</h6>
                                    <div class="fw-bold text-primary">৳{{ number_format($relPrice, 2) }}</div>
                                    <a href="{{ route('store.product.detail', $rel['id']) }}" class="btn btn-sm btn-outline-dark rounded-pill w-100 mt-2">View Product</a>
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
function addSingleToCart(id, name, price, img) {
    const qty = parseInt(document.getElementById('itemQty').value) || 1;
    let cart = JSON.parse(localStorage.getItem('deen_cart') || '[]');
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty += qty;
    } else {
        cart.push({ id, name, price, img, qty });
    }
    localStorage.setItem('deen_cart', JSON.stringify(cart));
    alert(name + ' (' + qty + ' pcs) added to your shopping bag!');
}
</script>
@endsection
