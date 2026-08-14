<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deen Commerce (দীন কমার্স) - Premier B2B Garments & StockLot E-Commerce Platform</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Deen Commerce Custom CSS -->
    <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Deen Commerce Navigation Bar -->
    <nav class="navbar navbar-expand-lg deen-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">DC</div>
                <div>
                    <span class="deen-logo-text">Deen Commerce</span>
                    <span class="deen-logo-tag ms-1">B2B StockLot</span>
                </div>
            </a>

            <button class="navbar-toggler text-white border-white" type="button" data-bs-toggle="collapse" data-bs-target="#deenNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="deenNavbar">
                <!-- Search Bar -->
                <form method="GET" action="{{ route('store.index') }}" class="mx-auto my-2 my-lg-0" style="max-width: 420px; width: 100%;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary rounded-start-pill px-3" placeholder="Search 800+ Deen Commerce garments..." value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary rounded-end-pill px-3" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="{{ route('woocommerce.dashboard') }}"><i class="fas fa-sync-alt me-1"></i> WooCommerce Hub</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('woocommerce.products') }}"><i class="fas fa-boxes me-1"></i> Catalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('woocommerce.orders') }}"><i class="fas fa-shopping-cart me-1"></i> Orders</a>
                    </li>
                    @guest
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light rounded-pill px-3" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary rounded-pill px-3" href="{{ route('register') }}">Join B2B</a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary rounded-pill px-3" href="{{ url('/home') }}"><i class="fas fa-user-circle me-1"></i> Dashboard</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Billboard Section -->
    <section class="deen-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="deen-hero-badge">
                        <i class="fas fa-bolt me-1 text-warning"></i> LIVE API CONNECTED &bull; https://deencommerce.com
                    </div>
                    <h1 class="deen-hero-title mb-3">
                        Bangladesh's Premier <span class="text-warning">Garments & StockLot</span> B2B Hub
                    </h1>
                    <p class="deen-hero-subtitle mb-4">
                        Discover 800+ WooCommerce product lines directly synced from Deen Commerce. Wholesale pricing, real-time stock verification, and bulk garment trading.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#products-showcase" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow">
                            <i class="fas fa-shopping-bag me-2"></i> Browse Live Products
                        </a>
                        <a href="{{ route('woocommerce.dashboard') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                            <i class="fas fa-chart-line me-2"></i> WooCommerce Hub
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="deen-stat-pill">
                                <div class="deen-stat-num">{{ number_format($totalProducts ?? 826) }}</div>
                                <div class="deen-stat-label">Woo Products</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="deen-stat-pill">
                                <div class="deen-stat-num">{{ $totalPages ?? 826 }}</div>
                                <div class="deen-stat-label">API Catalog Pages</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="deen-stat-pill">
                                <div class="deen-stat-num">100%</div>
                                <div class="deen-stat-label">Real-Time Stock</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="deen-stat-pill">
                                <div class="deen-stat-num">&lt; 200ms</div>
                                <div class="deen-stat-label">API Latency</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Storefront Content -->
    <main class="py-5" id="products-showcase">
        <div class="container">

            <!-- Category Pills Bar -->
            @if(!empty($categories))
                <div class="d-flex align-items-center gap-2 overflow-auto pb-3 mb-4 no-scrollbar">
                    <a href="{{ route('store.index') }}" class="deen-category-chip {{ empty($selectedCategory) ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> All Categories
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('store.index', ['category' => $cat['id']]) }}" class="deen-category-chip {{ ($selectedCategory == $cat['id']) ? 'active' : '' }}">
                            {{ $cat['name'] }} <span class="badge bg-secondary rounded-pill ms-1">{{ $cat['count'] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Live Products Showcase Header -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-boxes text-primary me-2"></i> Live Deen Commerce Catalog</h3>
                    <p class="text-muted small mb-0">Direct REST API stream from Deen Commerce database with real-time stock levels</p>
                </div>
                <div class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill font-monospace">
                    <i class="fas fa-signal me-1"></i> Live Data Feed Active
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4 mb-5">
                @forelse($products as $product)
                    @php
                        $image = $product['images'][0]['src'] ?? null;
                        $price = $product['price'] ?? 0;
                        $regularPrice = $product['regular_price'] ?? null;
                        $stockQty = $product['stock_quantity'] ?? null;
                        $stockStatus = $product['stock_status'] ?? 'instock';
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="deen-product-card">
                            <div class="deen-product-img-wrapper">
                                @if($image)
                                    <img src="{{ $image }}" class="deen-product-img" alt="{{ $product['name'] }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-tshirt fa-4x text-secondary opacity-50"></i>
                                    </div>
                                @endif
                                <span class="deen-badge-live">Woo #{{ $product['id'] }}</span>
                                @if($stockStatus === 'instock' || ($stockQty && $stockQty > 0))
                                    <span class="deen-badge-stock bg-success text-white">In Stock ({{ $stockQty ?? 'Avail' }})</span>
                                @else
                                    <span class="deen-badge-stock bg-danger text-white">Out of Stock</span>
                                @endif
                            </div>

                            <div class="deen-product-body">
                                <h5 class="deen-product-title" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>

                                <div class="deen-price-section">
                                    <span class="deen-price-current">৳{{ number_format((float)$price, 2) }}</span>
                                    @if($regularPrice && (float)$regularPrice > (float)$price)
                                        <span class="deen-price-old">৳{{ number_format((float)$regularPrice, 2) }}</span>
                                    @endif
                                </div>

                                <div class="deen-bulk-banner">
                                    <i class="fas fa-tag me-1"></i> B2B Wholesale Pricing Available
                                </div>

                                <div class="mt-auto d-grid gap-2">
                                    <button onclick="openProductModal({{ $product['id'] }})" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                                        <i class="fas fa-eye me-1"></i> Quick View
                                    </button>
                                    <a href="https://wa.me/?text=Inquiry%20for%20Product%20ID%20{{ $product['id'] }}" target="_blank" class="btn btn-primary btn-sm rounded-pill fw-bold">
                                        <i class="fab fa-whatsapp me-1"></i> Request B2B Quote
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="bg-white p-5 rounded-4 shadow-sm border">
                            <i class="fas fa-box-open fa-4x text-muted opacity-50 mb-3"></i>
                            <h4>No Products Found</h4>
                            <p class="text-muted">No Deen Commerce products matched your search parameters.</p>
                            <a href="{{ route('store.index') }}" class="btn btn-primary rounded-pill px-4">View All Products</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Bar -->
            @if(($totalPages ?? 1) > 1)
                <div class="d-flex justify-content-center align-items-center gap-2 mb-5">
                    @if($currentPage > 1)
                        <a href="{{ route('store.index', ['page' => $currentPage - 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn btn-outline-primary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Previous
                        </a>
                    @endif

                    <span class="fw-bold text-dark px-3">Page {{ $currentPage }} of {{ $totalPages }}</span>

                    @if($currentPage < $totalPages)
                        <a href="{{ route('store.index', ['page' => $currentPage + 1, 'search' => $searchQuery, 'category' => $selectedCategory]) }}" class="btn btn-primary rounded-pill px-3">
                            Next <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            @endif

            <!-- B2B Wholesale Quantity Pricing Calculator Widget -->
            <div class="deen-calc-box mt-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h4 class="fw-bold text-white mb-2"><i class="fas fa-calculator text-warning me-2"></i> Interactive B2B Volume Discount Calculator</h4>
                        <p class="text-white-50 mb-0">Estimate bulk order savings for Deen Commerce StockLots based on quantity volume tiers.</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-white text-dark p-4 rounded-4 shadow">
                            <div class="mb-3">
                                <label for="qtyRange" class="form-label font-weight-bold d-flex justify-content-between">
                                    <span>Select Order Quantity:</span>
                                    <span class="fw-bold text-primary" id="qtyVal">500 pcs</span>
                                </label>
                                <input type="range" class="form-range" min="50" max="5000" step="50" id="qtyRange" value="500" oninput="updateB2BCalc(this.value)">
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <div>
                                    <div class="small text-muted">Estimated Wholesale Discount:</div>
                                    <div class="fs-4 fw-bold text-success" id="discountVal">15% OFF</div>
                                </div>
                                <a href="https://deencommerce.com" target="_blank" class="btn btn-success fw-bold rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Confirm B2B Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

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
                        <p class="text-muted">Fetching live product details from Deen Commerce REST API...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5 border-top border-secondary">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold text-white mb-1">Deen Commerce B2B Marketplace</h5>
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} Deen Commerce REST API Integration. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-success px-3 py-2 rounded-pill small">
                        <i class="fas fa-plug me-1"></i> REST API Target: https://deencommerce.com
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function updateB2BCalc(qty) {
        document.getElementById('qtyVal').innerText = qty + ' pcs';
        let discount = 5;
        if (qty >= 2000) discount = 25;
        else if (qty >= 1000) discount = 20;
        else if (qty >= 500) discount = 15;
        else if (qty >= 200) discount = 10;
        document.getElementById('discountVal').innerText = discount + '% OFF';
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
                                <span class="badge bg-primary mb-2">WooCommerce ID #${p.id}</span>
                                <h4 class="fw-bold text-dark">${p.name}</h4>
                                <div class="fs-3 fw-bold text-primary mb-3">৳${p.price || '0.00'}</div>
                                <p class="text-muted small mb-3">${p.short_description || p.description || 'Deen Commerce Premium Garment StockLot product.'}</p>
                                <div class="mb-3">
                                    <span class="badge bg-success">Status: ${p.status || 'publish'}</span>
                                    <span class="badge bg-info text-dark">Stock: ${p.stock_quantity ?? 'Available'}</span>
                                    <span class="badge bg-dark">SKU: ${p.sku || 'N/A'}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="https://wa.me/?text=Inquiry%20for%20${encodeURIComponent(p.name)}" target="_blank" class="btn btn-success fw-bold rounded-pill">
                                        <i class="fab fa-whatsapp me-1"></i> Send B2B Wholesale Inquiry
                                    </a>
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