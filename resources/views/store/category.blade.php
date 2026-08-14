@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- Header Banner -->
        <div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-sm mb-5 position-relative overflow-hidden">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-danger text-white mb-2 px-3 py-2 rounded-pill fw-bold">Fashion Category</span>
                    <h1 class="fw-bold display-5 text-white mb-2">{{ $category['name'] ?? 'Category Products' }}</h1>
                    <p class="text-white-50 mb-0">{{ !empty($category['description']) ? strip_tags($category['description']) : 'Showing authentic apparel and denim lines from Deen Commerce.' }}</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-light text-dark px-3 py-2 fs-6 rounded-pill fw-bold">{{ number_format($totalProducts) }} Products</span>
                </div>
            </div>
        </div>

        <!-- Sort Bar -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border">
            <div class="fw-bold text-dark"><i class="fas fa-filter text-primary me-2"></i> Category Items</div>
            <form method="GET" action="{{ route('store.category', $category['id'] ?? 1) }}" class="d-flex align-items-center gap-2">
                <label for="sort" class="small fw-bold text-muted mb-0 me-1">Sort By:</label>
                <select name="sort" id="sort" class="form-select form-select-sm rounded-pill border-secondary" style="width: 180px;" onchange="this.form.submit()">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="row g-4 mb-5">
            @forelse($products as $product)
                @php
                    $image = $product['images'][0]['src'] ?? null;
                    $price = (float)($product['price'] ?? 0);
                    $regularPrice = isset($product['regular_price']) ? (float)$product['regular_price'] : null;
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
                            <span class="deen-stock-badge bg-success text-white">In Stock</span>
                        </div>

                        <div class="deen-retail-body">
                            <h5 class="deen-retail-title" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>

                            <div class="deen-rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <span class="text-muted ms-1 small">(5.0)</span>
                            </div>

                            <div class="deen-retail-price-row">
                                <span class="deen-retail-price">৳{{ number_format($price, 2) }}</span>
                                @if($regularPrice && $regularPrice > $price)
                                    <span class="deen-retail-old-price">৳{{ number_format($regularPrice, 2) }}</span>
                                @endif
                            </div>

                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('store.product.detail', $product['id']) }}" class="btn btn-dark rounded-pill fw-bold">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        <i class="fas fa-tshirt fa-4x text-muted opacity-50 mb-3"></i>
                        <h4>No Products in this Category</h4>
                        <p class="text-muted">No active items were found for this category.</p>
                        <a href="{{ route('store.categories') }}" class="btn btn-primary rounded-pill px-4">Browse Other Categories</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(($totalPages ?? 1) > 1)
            <div class="d-flex justify-content-center align-items-center gap-2">
                @if($page > 1)
                    <a href="{{ route('store.category', ['id' => $category['id'] ?? 1, 'page' => $page - 1, 'sort' => $sort]) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Previous
                    </a>
                @endif
                <span class="fw-bold text-dark px-3">Page {{ $page }} of {{ $totalPages }}</span>
                @if($page < $totalPages)
                    <a href="{{ route('store.category', ['id' => $category['id'] ?? 1, 'page' => $page + 1, 'sort' => $sort]) }}" class="btn btn-dark rounded-pill px-4 fw-bold">
                        Next <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
