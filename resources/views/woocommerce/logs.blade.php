@extends('layouts.admin')

@section('content')
<div class="woo-dashboard-wrapper">

    <!-- Hero Header -->
    <div class="woo-header-hero">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h1 class="woo-title"><i class="fas fa-receipt me-2"></i> REST API Audit Logs</h1>
                    <p class="woo-subtitle mb-0">Complete historical logging of WooCommerce API requests, status codes, and execution latencies</p>
                </div>
                <div>
                    <div class="woo-nav-tabs">
                        <a href="{{ route('woocommerce.dashboard') }}" class="woo-nav-link"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                        <a href="{{ route('woocommerce.products') }}" class="woo-nav-link"><i class="fas fa-boxes me-1"></i> Products</a>
                        <a href="{{ route('woocommerce.orders') }}" class="woo-nav-link"><i class="fas fa-shopping-cart me-1"></i> Orders</a>
                        <a href="{{ route('woocommerce.logs') }}" class="woo-nav-link active"><i class="fas fa-receipt me-1"></i> API Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-n4">
        <!-- Filter Bar -->
        <div class="woo-card p-3 mb-4">
            <form method="GET" action="{{ route('woocommerce.logs') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="endpoint" class="form-control bg-light border-start-0" placeholder="Filter by endpoint (e.g. products, orders)..." value="{{ request('endpoint') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="success" class="form-select bg-light">
                        <option value="">All Responses</option>
                        <option value="1" {{ request('success') === '1' ? 'selected' : '' }}>Successful (2xx)</option>
                        <option value="0" {{ request('success') === '0' ? 'selected' : '' }}>Failed / Error</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-woo-primary"><i class="fas fa-filter me-1"></i> Filter Logs</button>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="woo-card">
            <div class="table-responsive">
                <table class="table woo-table align-middle">
                    <thead>
                        <tr>
                            <th># ID</th>
                            <th>Method & Endpoint</th>
                            <th>Status Code</th>
                            <th>Response Time</th>
                            <th>Query Parameters</th>
                            <th>Error Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><span class="text-muted small">#{{ $log->id }}</span></td>
                                <td>
                                    <span class="badge bg-dark font-monospace me-1">{{ $log->method }}</span>
                                    <span class="fw-bold text-dark font-monospace">{{ $log->endpoint }}</span>
                                </td>
                                <td>
                                    @if($log->success)
                                        <span class="woo-badge woo-badge-success"><i class="fas fa-check-circle me-1"></i> {{ $log->status_code ?? 200 }}</span>
                                    @else
                                        <span class="woo-badge woo-badge-danger"><i class="fas fa-exclamation-triangle me-1"></i> {{ $log->status_code ?? 'Failed' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->response_time_ms < 300)
                                        <span class="badge bg-success-subtle text-success border border-success font-monospace"><i class="fas fa-bolt me-1"></i> {{ $log->response_time_ms }} ms</span>
                                    @elseif($log->response_time_ms < 1000)
                                        <span class="badge bg-warning-subtle text-warning border border-warning font-monospace">{{ $log->response_time_ms }} ms</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger font-monospace">{{ $log->response_time_ms }} ms</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->query && !empty($log->query))
                                        <code class="small text-muted" title="{{ json_encode($log->query) }}">{{ Str::limit(json_encode($log->query), 30) }}</code>
                                    @else
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->error_message)
                                        <span class="text-danger small font-monospace" title="{{ $log->error_message }}">{{ Str::limit($log->error_message, 40) }}</span>
                                    @else
                                        <span class="text-success small"><i class="fas fa-check me-1"></i> Clean</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-dark">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                                    <div class="small text-muted">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-muted opacity-50 mb-3 d-block"></i>
                                    <h5>No API Request Logs Found</h5>
                                    <p class="text-muted">No API request logs recorded yet. Logs are stored automatically whenever WooCommerce API requests are made.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
