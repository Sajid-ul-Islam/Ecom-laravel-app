@extends('layouts.app')

@section('content')
<div class="deen-section-py-lg">
 <div class="container">
 <!-- Header Banner -->
 <div class="deen-frame deen-pastel-azure p-4 p-md-5 mb-5 position-relative overflow-hidden">
 <div class="row align-items-center g-4">
 <div class="col-md-8">
 <span class="deen-vibrant-pill indigo mb-3">Collection Index</span>
 <h1 class="deen-title-lg mb-2"><span class="deen-gradient-text">{{ $category['name'] ?? 'Curated Line' }}</span></h1>
 <p class="text-secondary mb-0">
 {{ !empty($category['description']) ? strip_tags($category['description']) : 'Authentic retail apparel lines crafted with premium heavyweight denim and natural weaves.' }}
 </p>
 </div>
 <div class="col-md-4 text-md-end">
 <span class="deen-vibrant-pill emerald fs-6">
 <i class="fas fa-boxes-stacked me-1"></i> {{ number_format($totalProducts) }} Active Pieces
 </span>
 </div>
 </div>
 </div>

 <!-- Sort & Filter Bar -->
 <div class="deen-frame p-3 mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
 <div class="d-flex align-items-center gap-2 text-secondary small fw-semibold">
 <span class="material-symbols-outlined fs-5">filter_list</span>
 <span>Displaying live WooCommerce catalog items</span>
 </div>
 <form method="GET" action="{{ route('store.category', $category['id'] ?? 1) }}" class="d-flex align-items-center gap-2">
 <label for="sort" class="small fw-semibold text-secondary mb-0 me-1">Sort Line:</label>
 <select name="sort" id="sort" class="form-select form-select-sm rounded-pill border-0 bg-light px-3 py-1 fw-semibold text-dark shadow-none" onchange="this.form.submit()">
 <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
 <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
 <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
 </select>
 </form>
 </div>

 <!-- Products Grid (2-Column Mobile Grid) -->
 <div class="row g-3 g-md-4 mb-5">
 @forelse($products as $product)
 @php
 $image = $product['images'][0]['src'] ?? null;
 $price = (float)($product['price'] ?? 0);
 $regularPrice = isset($product['regular_price']) ? (float)$product['regular_price'] : null;
 @endphp
 <div class="col-6 col-md-4 col-lg-3">
 <div class="deen-retail-card position-relative">
 <div class="deen-retail-img-box deen-card-video-box">
 @if($image)
 <img src="{{ $image }}" class="deen-retail-img deen-card-main-img" alt="{{ $product['name'] }}" loading="lazy">
 @else
 <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-secondary">
 <span class="material-symbols-outlined fs-1 opacity-40">apparel</span>
 </div>
 @endif

 <!-- Subtle Fabric & Movement Video Snippet -->
 <video class="deen-card-video" muted loop playsinline preload="none" poster="{{ $image }}">
 <source src="https://assets.mixkit.co/videos/preview/mixkit-fashion-model-in-a-denim-jacket-and-pants-40763-small.mp4" type="video/mp4">
 </video>
 <span class="deen-video-badge"><i class="fas fa-play"></i> Motion</span>

 <!-- Wishlist Heart Toggle Button -->
 <button type="button" class="deen-wishlist-btn" data-id="{{ $product['id'] }}" onclick="toggleWishlist({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}', this)" title="Save to Favorites">
 <i class="fas fa-heart"></i>
 </button>

 </div>

 <div class="deen-retail-body">
 <!-- Instant Fabric & Wash Swatches --><div class="deen-card-swatches" role="radiogroup" aria-label="Select fabric wash color">
 <button type="button" class="deen-swatch-dot swatch-indigo active" onclick="swapCategoryCardImage(this, '{{ $image }}')" title="Raw Indigo" role="radio" aria-checked="true" aria-label="Raw Indigo"></button>
 <button type="button" class="deen-swatch-dot swatch-vintage" onclick="swapCategoryCardImage(this, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&auto=format&fit=crop')" title="Vintage Light Wash" role="radio" aria-checked="false" aria-label="Vintage Light Wash"></button>
 <button type="button" class="deen-swatch-dot swatch-black" onclick="swapCategoryCardImage(this, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop')" title="Stonewashed Black" role="radio" aria-checked="false" aria-label="Stonewashed Black"></button>
 <button type="button" class="deen-swatch-dot swatch-ecru" onclick="swapCategoryCardImage(this, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop')" title="Raw Ecru" role="radio" aria-checked="false" aria-label="Raw Ecru"></button>
 </div>

 <h3 class="deen-retail-title" title="{{ $product['name'] }}">{{ $product['name'] }}</h3>

 <div class="deen-retail-price-row mb-3">
 <span class="deen-retail-price">৳{{ number_format($price, 2) }}</span>
 @if($regularPrice && $regularPrice > $price)
 <span class="deen-retail-old-price">৳{{ number_format($regularPrice, 2) }}</span>
 @endif
 </div>

 <div class="mt-auto d-grid gap-2">
 <div class="d-flex gap-2">
 <button type="button" onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $price }}, '{{ addslashes($image) }}')" class="btn-deen-primary flex-grow-1 py-2">
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
 <div class="deen-frame deen-pastel-linen p-5 max-w-lg mx-auto">
 <span class="material-symbols-outlined fs-1 text-secondary mb-3">inventory_2</span>
 <h3 class="deen-title-sm mb-2">No Pieces in this Line</h3>
 <p class="text-secondary small mb-4">No active catalog items were found in this specific collection.</p>
 <a href="{{ route('store.categories') }}" class="btn-deen-primary">Browse All Collections</a>
 </div>
 </div>
 @endforelse
 </div>

 <!-- Sizing Estimator Mini Banner -->
 <div class="deen-frame deen-pastel-sand p-4 my-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
 <div class="d-flex align-items-center gap-3">
 <span class="material-symbols-outlined fs-2 text-dark">straighten</span>
 <div>
 <div class="fw-bold text-dark small">Unsure about fits across our collection?</div>
 <div class="text-secondary small">Use our 2-Step Sizing Estimator to calculate your exact denim size.</div>
 </div>
 </div>
 <button type="button" class="btn-deen-primary btn-sm py-2 px-3" onclick="openSizeCalculator()">
 Check My Size <i class="fas fa-ruler-combined ms-1"></i>
 </button>
 </div>

 <!-- Pagination -->
 @if(($totalPages ?? 1) > 1)
 <div class="d-flex justify-content-center align-items-center gap-3 pt-3">
 @if($page > 1)
 <a href="{{ route('store.category', ['id' => $category['id'] ?? 1, 'page' => $page - 1, 'sort' => $sort]) }}" class="btn-deen-outline">
 <i class="fas fa-arrow-left me-1"></i> Previous
 </a>
 @endif
 <span class="deen-pastel-pill azure px-3 py-2">Page {{ $page }} of {{ $totalPages }}</span>
 @if($page < $totalPages)
 <a href="{{ route('store.category', ['id' => $category['id'] ?? 1, 'page' => $page + 1, 'sort' => $sort]) }}" class="btn-deen-primary">
 Next <i class="fas fa-arrow-right ms-1"></i>
 </a>
 @endif
 </div>
 @endif
 </div>
</div>

<script>
function swapCategoryCardImage(swatchEl, newImgUrl) {
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

function addCategoryItemToCart(id, name, price, img) {
 if (typeof addToCart === 'function') {
 addToCart(id, name, price, img);
 return;
 }
 let cart = getStoredCart();
 const existing = cart.find(item => item.id === id);
 if (existing) {
 existing.qty = (existing.qty || 1) + 1;
 } else {
 cart.push({ id, name, price, img, qty: 1, size: 'M' });
 }
 localStorage.setItem('deen_cart', JSON.stringify(cart));
 if (typeof syncCartBadges === 'function') {
 syncCartBadges();
 }
}
const addCategoryItemToBag = addCategoryItemToCart;

document.addEventListener('DOMContentLoaded', () => {
 // Setup Dynamic Video Card Hover & Autoplay on Viewport
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
@endsection



