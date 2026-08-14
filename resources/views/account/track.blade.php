@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container" style="max-width: 900px;">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <span class="badge bg-danger text-white mb-2 px-3 py-2 rounded-pill fw-bold">Live Order Tracker</span>
                <h1 class="fw-bold text-dark display-6 mb-1">Track Order #{{ $order['order_id'] }}</h1>
                <p class="text-muted mb-0">Real-time status updates from Deen Commerce logistics & courier partners</p>
            </div>
            <a href="{{ route('account.orders') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>

        <!-- 5-STAGE PROGRESS TRACKER CARD -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white mb-4">
            <div class="mb-4 text-center text-md-start">
                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-truck-fast me-1"></i> STATUS: IN TRANSIT WITH COURIER
                </span>
            </div>

            <!-- Stepper Bar -->
            <div class="position-relative my-4">
                <div class="progress position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 6px; z-index: 1;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 60%;"></div>
                </div>

                <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                    <!-- Step 1: Order Placed -->
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="fw-bold text-dark small mt-2">Order Placed</div>
                        <div class="text-muted micro-text">Completed</div>
                    </div>

                    <!-- Step 2: Processing -->
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px;">
                            <i class="fas fa-box-archive"></i>
                        </div>
                        <div class="fw-bold text-dark small mt-2">Processing</div>
                        <div class="text-muted micro-text">Quality Checked</div>
                    </div>

                    <!-- Step 3: In Transit -->
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-lg ring-pulse" style="width: 44px; height: 44px;">
                            <i class="fas fa-truck-fast"></i>
                        </div>
                        <div class="fw-bold text-primary small mt-2">In Transit</div>
                        <div class="text-muted micro-text">At Courier Hub</div>
                    </div>

                    <!-- Step 4: Out for Delivery -->
                    <div class="text-center">
                        <div class="bg-light text-muted border border-2 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                            <i class="fas fa-person-biking"></i>
                        </div>
                        <div class="fw-bold text-muted small mt-2">Out for Delivery</div>
                        <div class="text-muted micro-text">Pending</div>
                    </div>

                    <!-- Step 5: Delivered -->
                    <div class="text-center">
                        <div class="bg-light text-muted border border-2 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                            <i class="fas fa-house-chimney-check"></i>
                        </div>
                        <div class="fw-bold text-muted small mt-2">Delivered</div>
                        <div class="text-muted micro-text">Pending</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COURIER & SHIPPING INFO CARD -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-truck text-danger me-2"></i> Logistics & Courier Partner</h5>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="fw-bold text-dark fs-5">{{ $order['courier']['name'] }}</div>
                        <div class="text-muted small">Tracking ID: <strong class="text-dark font-monospace">{{ $order['courier']['tracking_code'] }}</strong></div>
                        <div class="text-muted small">Current Location: <span class="text-primary fw-semibold">{{ $order['courier']['current_hub'] }}</span></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Est. Delivery Date:</span>
                        <strong class="text-dark">{{ $order['courier']['estimated_delivery'] }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-location-dot text-primary me-2"></i> Delivery Destination</h5>
                    <div class="fw-bold text-dark mb-1">{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</div>
                    <div class="text-muted small mb-1"><i class="fas fa-phone me-1"></i> {{ $order['customer']['phone'] }}</div>
                    <div class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i> {{ $order['customer']['address'] }}, {{ $order['customer']['city'] }}</div>
                    <div class="badge bg-light text-dark border align-self-start px-3 py-2 rounded-pill">
                        Payment: {{ $order['payment_method'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMIZED RECEIPT SUMMARY -->
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-shopping-bag text-primary me-2"></i> Order Items</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order['items'] as $item)
                            <tr>
                                <td class="fw-bold text-dark">{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['qty'] }}</td>
                                <td class="text-end">৳{{ number_format($item['price'], 2) }}</td>
                                <td class="text-end fw-bold">৳{{ number_format($item['price'] * $item['qty'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <a href="{{ route('store.order.success', $order['order_id']) }}" class="btn btn-outline-dark rounded-pill fw-bold">
                    <i class="fas fa-print me-1"></i> View Receipt Invoice
                </a>
                <div class="fs-4 fw-bold text-dark">
                    Total: <span class="text-danger">৳{{ number_format($order['total'], 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.ring-pulse {
    box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
    animation: pulse 1.6s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
}
.micro-text { font-size: 0.7rem; }
</style>
@endsection
