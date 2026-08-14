@extends('layouts.app')

@section('content')
<link href="{{ asset('css/woocommerce-dashboard.css') }}" rel="stylesheet">

<div class="woo-dashboard-wrapper">
    <!-- Hero Header -->
    <div class="woo-header-hero">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h1 class="woo-title"><i class="fas fa-shopping-cart me-2"></i> Synced WooCommerce Orders</h1>
                    <p class="woo-subtitle mb-0">Filtered order stream syncing only completed and processing WooCommerce orders</p>
                </div>
                <div>
                    <div class="woo-nav-tabs">
                        <a href="{{ route('woocommerce.dashboard') }}" class="woo-nav-link"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                        <a href="{{ route('woocommerce.products') }}" class="woo-nav-link"><i class="fas fa-boxes me-1"></i> Products</a>
                        <a href="{{ route('woocommerce.orders') }}" class="woo-nav-link active"><i class="fas fa-shopping-cart me-1"></i> Orders</a>
                        <a href="{{ route('woocommerce.logs') }}" class="woo-nav-link"><i class="fas fa-receipt me-1"></i> API Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-n4">
        <!-- Search & Filter Card -->
        <div class="woo-card p-3 mb-4">
            <form method="GET" action="{{ route('woocommerce.orders') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by order number, Woo ID, or customer email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">All Synced Statuses</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-woo-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <!-- Orders Table / List -->
        <div class="woo-card">
            <div class="table-responsive">
                <table class="table woo-table align-middle">
                    <thead>
                        <tr>
                            <th>Order Details</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Line Items</th>
                            <th>Total Amount</th>
                            <th>Woo Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark fs-6">Order #{{ $order->number ?? $order->woo_id }}</div>
                                    <div class="small text-muted font-monospace">Woo ID: {{ $order->woo_id }}</div>
                                    @if($order->payment_method_title)
                                        <div class="badge bg-light text-dark border mt-1"><i class="fas fa-credit-card me-1"></i> {{ $order->payment_method_title }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $order->customer_email ?? 'Guest Customer' }}</div>
                                    @if(isset($order->billing['first_name']))
                                        <div class="small text-muted">{{ $order->billing['first_name'] }} {{ $order->billing['last_name'] ?? '' }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span class="woo-badge woo-badge-success"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                    @elseif($order->status === 'processing')
                                        <span class="woo-badge woo-badge-info"><i class="fas fa-spinner fa-spin me-1"></i> Processing</span>
                                    @else
                                        <span class="woo-badge woo-badge-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        @foreach($order->items as $item)
                                            <div class="text-truncate" style="max-width: 220px;" title="{{ $item->name }}">
                                                <span class="fw-bold">{{ $item->quantity }}x</span> {{ $item->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $order->currency ?? '$' }} {{ number_format($order->total ?? 0, 2) }}</div>
                                    <div class="small text-muted">Subtotal: {{ number_format($order->subtotal ?? 0, 2) }}</div>
                                </td>
                                <td>
                                    <div class="small text-dark">{{ $order->woo_created_at ? $order->woo_created_at->format('M d, Y H:i') : 'N/A' }}</div>
                                    <div class="small text-muted">{{ $order->last_synced_at?->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted opacity-50 mb-3 d-block"></i>
                                    <h5>No Synced Orders Found</h5>
                                    <p class="text-muted">Only orders with <code>processing</code> or <code>completed</code> status are synced from WooCommerce.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
