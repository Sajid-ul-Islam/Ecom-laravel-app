@extends('layouts.admin')

@section('content')
<div class="woo-dashboard-wrapper">

    <!-- Hero Header -->
    <div class="woo-header-hero">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="woo-live-dot"></span>
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill small">WooCommerce Connected</span>
                        <span class="text-white-50 small">Target: {{ config('woocommerce.url') }}</span>
                    </div>
                    <h1 class="woo-title">WooCommerce Integration Hub</h1>
                    <p class="woo-subtitle mb-0">Real-time REST API Synchronization & B2B StockLot Management System</p>
                </div>
                <div>
                    <div class="woo-nav-tabs">
                        <a href="{{ route('woocommerce.dashboard') }}" class="woo-nav-link active"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                        <a href="{{ route('woocommerce.products') }}" class="woo-nav-link"><i class="fas fa-boxes me-1"></i> Products</a>
                        <a href="{{ route('woocommerce.orders') }}" class="woo-nav-link"><i class="fas fa-shopping-cart me-1"></i> Orders</a>
                        <a href="{{ route('woocommerce.logs') }}" class="woo-nav-link"><i class="fas fa-receipt me-1"></i> API Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-n4">
        <!-- System Status Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="ajax-alert" class="d-none alert alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
            <span id="ajax-alert-message"></span>
            <button type="button" class="btn-close" onclick="document.getElementById('ajax-alert').classList.add('d-none')"></button>
        </div>

        <!-- Metric Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="woo-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="woo-stat-label">Synced Products</span>
                        <div class="woo-stat-icon primary"><i class="fas fa-box-open"></i></div>
                    </div>
                    <div class="woo-stat-value">{{ number_format($totalProducts) }}</div>
                    <div class="small text-muted mt-2">
                        <span class="text-success fw-bold">{{ number_format($publishedProducts) }}</span> Published
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="woo-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="woo-stat-label">Synced Orders</span>
                        <div class="woo-stat-icon success"><i class="fas fa-shopping-bag"></i></div>
                    </div>
                    <div class="woo-stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="small text-muted mt-2">
                        <span class="text-info fw-bold">{{ number_format($processingOrders) }}</span> Processing | 
                        <span class="text-success fw-bold">{{ number_format($completedOrders) }}</span> Completed
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="woo-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="woo-stat-label">Total Stock Quantity</span>
                        <div class="woo-stat-icon accent"><i class="fas fa-cubes"></i></div>
                    </div>
                    <div class="woo-stat-value">{{ number_format($totalStockQuantity) }}</div>
                    <div class="small text-muted mt-2">
                        <i class="fas fa-sync-alt me-1 text-primary"></i> Real-time inventory sync
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="woo-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="woo-stat-label">Avg API Latency</span>
                        <div class="woo-stat-icon warning"><i class="fas fa-bolt"></i></div>
                    </div>
                    <div class="woo-stat-value">{{ $avgResponseTime }} <small class="fs-6 text-muted">ms</small></div>
                    <div class="small text-muted mt-2">
                        <span class="fw-bold text-dark">{{ number_format($totalApiLogs) }}</span> API requests logged
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync Control Panel -->
        <div class="woo-card p-4 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fas fa-play-circle text-primary me-2"></i> Manual Sync Controls</h5>
                    <p class="text-muted small mb-0">Trigger full or partial synchronization directly via REST API with exponential backoff handling.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button onclick="triggerWooSync('all')" class="btn btn-woo-primary btn-sync-action">
                        <i class="fas fa-sync me-1"></i> Sync All Data
                    </button>
                    <button onclick="triggerWooSync('products')" class="btn btn-woo-secondary btn-sync-action">
                        <i class="fas fa-boxes me-1"></i> Sync Products
                    </button>
                    <button onclick="triggerWooSync('orders')" class="btn btn-woo-secondary btn-sync-action">
                        <i class="fas fa-receipt me-1"></i> Sync Orders
                    </button>
                    <button onclick="triggerWooSync('stock')" class="btn btn-woo-secondary btn-sync-action">
                        <i class="fas fa-warehouse me-1"></i> Real-time Stock
                    </button>
                    @if($unresolvedFailures > 0)
                        <button onclick="retryWooFailures()" class="btn btn-danger btn-sync-action">
                            <i class="fas fa-redo me-1"></i> Retry {{ $unresolvedFailures }} Failures
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dead-Letter Failures Warning Widget -->
        @if($unresolvedFailures > 0)
            <div class="woo-card border-danger mb-4">
                <div class="woo-card-header bg-danger text-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Dead-Letter Queue: {{ $unresolvedFailures }} Unresolved Failures</h6>
                    <button onclick="retryWooFailures()" class="btn btn-sm btn-light text-danger fw-bold">Retry All Now</button>
                </div>
                <div class="table-responsive">
                    <table class="table woo-table">
                        <thead>
                            <tr>
                                <th>Entity</th>
                                <th>Woo ID</th>
                                <th>Error Message</th>
                                <th>Attempts</th>
                                <th>Last Attempted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFailures as $failure)
                                <tr>
                                    <td><span class="badge bg-danger text-uppercase">{{ $failure->entity_type }}</span></td>
                                    <td><strong>#{{ $failure->woo_id ?? 'N/A' }}</strong></td>
                                    <td class="text-danger small">{{ $failure->error_message }}</td>
                                    <td><span class="badge bg-secondary">{{ $failure->attempts }}</span></td>
                                    <td class="small text-muted">{{ $failure->last_attempted_at?->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Recent API Logs Activity Stream -->
            <div class="col-12 col-lg-7">
                <div class="woo-card h-100 mb-0">
                    <div class="woo-card-header">
                        <h6 class="woo-card-title"><i class="fas fa-list-alt text-primary me-2"></i> Recent REST API Activity</h6>
                        <a href="{{ route('woocommerce.logs') }}" class="btn btn-sm btn-link text-decoration-none">View All Logs</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table woo-table align-middle">
                            <thead>
                                <tr>
                                    <th>Method & Endpoint</th>
                                    <th>Status</th>
                                    <th>Latency</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark me-1">{{ $log->method }}</span>
                                            <span class="fw-medium">{{ $log->endpoint }}</span>
                                        </td>
                                        <td>
                                            @if($log->success)
                                                <span class="woo-badge woo-badge-success"><i class="fas fa-check-circle"></i> {{ $log->status_code ?? 200 }}</span>
                                            @else
                                                <span class="woo-badge woo-badge-danger"><i class="fas fa-times-circle"></i> {{ $log->status_code ?? 'Err' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-monospace">{{ $log->response_time_ms }} ms</span>
                                        </td>
                                        <td class="small text-muted">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No API request logs recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Price History Stream -->
            <div class="col-12 col-lg-5">
                <div class="woo-card h-100 mb-0">
                    <div class="woo-card-header">
                        <h6 class="woo-card-title"><i class="fas fa-tags text-accent me-2"></i> Price Change History</h6>
                        <span class="badge bg-secondary">Tracked Changes</span>
                    </div>
                    <div class="p-3">
                        @forelse($recentPriceChanges as $history)
                            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-3 bg-light border">
                                <div>
                                    <div class="fw-bold text-dark small">{{ $history->product?->name ?? 'Woo ID #' . $history->woo_id }}</div>
                                    <div class="small text-muted">Changed {{ $history->changed_at?->diffForHumans() }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-decoration-line-through text-muted small">${{ number_format($history->old_price, 2) }}</div>
                                    <div class="fw-bold text-success">${{ number_format($history->new_price, 2) }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">No price change history recorded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function triggerWooSync(type) {
    const buttons = document.querySelectorAll('.btn-sync-action');
    buttons.forEach(btn => btn.disabled = true);

    const alertBox = document.getElementById('ajax-alert');
    const alertMsg = document.getElementById('ajax-alert-message');
    alertBox.className = 'alert alert-info alert-dismissible fade show shadow-sm rounded-4 mb-4';
    alertMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Synchronizing WooCommerce ' + type + ' data via REST API...';

    fetch("{{ route('woocommerce.sync') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        buttons.forEach(btn => btn.disabled = false);
        if (data.success) {
            alertBox.className = 'alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4';
            alertMsg.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data.message;
            setTimeout(() => window.location.reload(), 1500);
        } else {
            alertBox.className = 'alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4';
            alertMsg.innerHTML = '<i class="fas fa-times-circle me-2"></i> ' + data.message;
        }
    })
    .catch(error => {
        buttons.forEach(btn => btn.disabled = false);
        alertBox.className = 'alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4';
        alertMsg.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Request failed: ' + error;
    });
}

function retryWooFailures() {
    const alertBox = document.getElementById('ajax-alert');
    const alertMsg = document.getElementById('ajax-alert-message');
    alertBox.className = 'alert alert-info alert-dismissible fade show shadow-sm rounded-4 mb-4';
    alertMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Retrying dead-letter queue failures...';

    fetch("{{ route('woocommerce.retry-failures') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alertBox.className = 'alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4';
            alertMsg.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data.message;
            setTimeout(() => window.location.reload(), 1500);
        } else {
            alertBox.className = 'alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4';
            alertMsg.innerHTML = '<i class="fas fa-times-circle me-2"></i> ' + data.message;
        }
    });
}
</script>
@endsection
