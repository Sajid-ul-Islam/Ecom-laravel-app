@extends('layouts.admin')

@section('content')
<div class="woo-dashboard-wrapper">

    <!-- Hero Header -->
    <div class="woo-header-hero">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h1 class="woo-title"><i class="fas fa-boxes me-2"></i> Synced Products & StockLots</h1>
                    <p class="woo-subtitle mb-0">WooCommerce product catalog with real-time stock levels and price change history</p>
                </div>
                <div>
                    <div class="woo-nav-tabs">
                        <a href="{{ route('woocommerce.dashboard') }}" class="woo-nav-link"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                        <a href="{{ route('woocommerce.products') }}" class="woo-nav-link active"><i class="fas fa-boxes me-1"></i> Products</a>
                        <a href="{{ route('woocommerce.orders') }}" class="woo-nav-link"><i class="fas fa-shopping-cart me-1"></i> Orders</a>
                        <a href="{{ route('woocommerce.logs') }}" class="woo-nav-link"><i class="fas fa-receipt me-1"></i> API Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-n4">
        <!-- Search & Filter Card -->
        <div class="woo-card p-3 mb-4">
            <form method="GET" action="{{ route('woocommerce.products') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by name, SKU, or WooCommerce ID..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">All Statuses</option>
                        <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Published</option>
                        <option value="trash" {{ request('status') == 'trash' ? 'selected' : '' }}>Archived (Trashed)</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="stock_status" class="form-select bg-light">
                        <option value="">All Stock</option>
                        <option value="instock" {{ request('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                        <option value="outofstock" {{ request('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-woo-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="woo-product-card">
                        <div class="position-relative">
                            @if($product->featured_image)
                                <img src="{{ $product->featured_image }}" class="woo-product-img" alt="{{ $product->name }}">
                            @else
                                <div class="woo-product-img d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-box-open fa-3x text-muted opacity-50"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark opacity-90">Woo ID #{{ $product->woo_id }}</span>
                            @if($product->trashed() || $product->status === 'trash')
                                <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Archived</span>
                            @elseif($product->status === 'publish')
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success">Published</span>
                            @else
                                <span class="position-absolute top-0 end-0 m-2 badge bg-secondary">{{ ucfirst($product->status) }}</span>
                            @endif
                        </div>

                        <div class="woo-product-body">
                            <h6 class="woo-product-title text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h6>
                            <div class="small text-muted mb-2 font-monospace">SKU: {{ $product->sku ?? 'N/A' }}</div>

                            <div class="d-flex align-items-baseline justify-content-between mb-3">
                                <div>
                                    <span class="woo-price-tag">${{ number_format($product->price ?? 0, 2) }}</span>
                                    @if($product->regular_price && $product->regular_price > $product->price)
                                        <span class="small text-decoration-line-through text-muted ms-1">${{ number_format($product->regular_price, 2) }}</span>
                                    @endif
                                </div>
                                @if($product->priceHistories->count() > 0)
                                    <span class="badge bg-warning text-dark small" title="Price changes recorded"><i class="fas fa-history me-1"></i> {{ $product->priceHistories->count() }} edits</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                <span class="small font-weight-bold">
                                    @if($product->stock_status === 'instock' || ($product->stock_quantity && $product->stock_quantity > 0))
                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i> {{ $product->stock_quantity ?? 0 }} in stock</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Out of stock</span>
                                    @endif
                                </span>
                                <span class="small text-muted" title="Last synced">{{ $product->last_synced_at?->diffForHumans() ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="woo-card p-5">
                        <i class="fas fa-boxes fa-4x text-muted opacity-50 mb-3"></i>
                        <h5>No Synced Products Found</h5>
                        <p class="text-muted">No WooCommerce products match your query. Click "Sync All Data" on the dashboard to fetch products.</p>
                        <a href="{{ route('woocommerce.dashboard') }}" class="btn btn-woo-primary"><i class="fas fa-sync me-1"></i> Go to Sync Hub</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
