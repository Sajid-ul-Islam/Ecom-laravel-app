@extends('layouts.app')

@section('content')
<div class="py-5">
 <div class="container py-2" style="max-width: 960px;">

 <!-- Header -->
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
 <div>
 <span class="deen-vibrant-pill orange py-0.5 px-2.5 mb-1.5 d-inline-flex align-items-center gap-1">
 <span class="material-symbols-outlined">local_shipping</span>
 <span>LIVE COURIER RADAR</span>
 </span>
 <h1 class="h3 fw-bold text-dark mb-1 font-display">Track Order #{{ $order['order_id'] }}</h1>
 <p class="text-secondary small mb-0">Real-time status updates synced with Deen Commerce logistics & courier partners</p>
 </div>
 <div class="d-flex align-items-center gap-2">
 <a href="{{ route('account.dashboard') }}" class="btn btn-outline-dark rounded-pill px-3.5 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">person</span>
 <span>Client Profile</span>
 </a>
 <a href="{{ route('account.orders') }}" class="btn btn-dark rounded-pill px-3.5 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">inventory_2</span>
 <span>All Orders</span>
 </a>
 </div>
 </div>

 <!-- 5-STAGE PROGRESS TRACKER CARD -->
 <div class="deen-frame p-4 p-md-5 bg-white mb-4 shadow-sm">
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 pb-3 mb-4 border-bottom">
 <div class="d-flex align-items-center gap-2">
 <span class="deen-vibrant-pill emerald py-0.5 px-2.5 d-inline-flex align-items-center gap-1">
 <span class="material-symbols-outlined">local_shipping</span>
 <span>STATUS: IN TRANSIT WITH COURIER</span>
 </span>
 <span class="text-secondary small">&bull; Placed: {{ $order['created_at'] }}</span>
 </div>
 <div class="text-md-end">
 <span class="text-secondary small">Est. Delivery:</span>
 <span class="fw-bold text-dark font-display ms-1">{{ $order['courier']['estimated_delivery'] }}</span>
 </div>
 </div>

 <!-- Stepper Bar -->
 <div class="position-relative my-5 px-2 px-md-4">
 <div class="position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 4px; background: #e2e8f0; z-index: 1;">
 <div class="h-100" style="width: 60%; background: linear-gradient(90deg, #10b981 0%, #2563eb 100%);"></div>
 </div>

 <div class="d-flex justify-content-between position-relative">
 <!-- Step 1: Order Placed -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white shadow-sm">
 <span class="material-symbols-outlined fs-5">check</span>
 </div>
 <div class="fw-bold text-dark small mt-2">Order Placed</div>
 <div class="text-secondary">Confirmed</div>
 </div>

 <!-- Step 2: Quality Inspection -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white shadow-sm">
 <span class="material-symbols-outlined fs-5">verified</span>
 </div>
 <div class="fw-bold text-dark small mt-2">QC Inspected</div>
 <div class="text-secondary">Dhaka Hub</div>
 </div>

 <!-- Step 3: In Transit -->
 <div class="text-center"><div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white shadow-lg ring-pulse" role="img" aria-label="Step 3: In Transit - Current status">
                                        <span class="material-symbols-outlined fs-5" aria-hidden="true">local_shipping</span>
                                    </div>
 <div class="fw-bold text-primary small mt-2">In Transit</div>
 <div class="text-secondary">At Courier Hub</div>
 </div>

 <!-- Step 4: Out for Delivery -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light border text-muted">
 <span class="material-symbols-outlined fs-5">two_wheeler</span>
 </div>
 <div class="fw-semibold text-muted small mt-2">Out for Delivery</div>
 <div class="text-secondary">Pending</div>
 </div>

 <!-- Step 5: Delivered -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light border text-muted">
 <span class="material-symbols-outlined fs-5">home</span>
 </div>
 <div class="fw-semibold text-muted small mt-2">Delivered</div>
 <div class="text-secondary">Pending</div>
 </div>
 </div>
 </div>
 </div>

 <!-- COURIER & SHIPPING INFO CARD -->
 <div class="row g-4 mb-4">
 <div class="col-md-6">
 <div class="deen-frame p-4 bg-white h-100 shadow-sm">
 <div class="d-flex align-items-center gap-2 mb-3">
 <span class="material-symbols-outlined text-danger fs-5">local_shipping</span>
 <h4 class="h6 fw-bold text-dark mb-0">Logistics & Courier Partner</h4>
 </div>
 <div class="p-3 bg-light rounded-3 mb-3 border">
 <div class="fw-bold text-dark fs-6">{{ $order['courier']['name'] }}</div>
 <div class="text-secondary small mt-1">Tracking ID: <strong class="text-dark font-monospace">{{ $order['courier']['tracking_code'] }}</strong></div>
 <div class="text-secondary small">Current Hub: <span class="text-primary fw-semibold">{{ $order['courier']['current_hub'] }}</span></div>
 </div>
 <div class="d-flex justify-content-between text-secondary small mb-2">
 <span>Estimated Arrival:</span>
 <strong class="text-dark">{{ $order['courier']['estimated_delivery'] }}</strong>
 </div>
 <a href="https://steadfast.com.bd/t/{{ $order['courier']['tracking_code'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 shadow-none">
 <span>Track on Courier Partner Site</span>
 <span class="material-symbols-outlined fs-6">open_in_new</span>
 </a>
 </div>
 </div>

 <div class="col-md-6">
 <div class="deen-frame p-4 bg-white h-100 shadow-sm">
 <div class="d-flex align-items-center gap-2 mb-3">
 <span class="material-symbols-outlined text-primary fs-5">location_on</span>
 <h4 class="h6 fw-bold text-dark mb-0">Delivery Destination & Contact</h4>
 </div>
 <div class="fw-bold text-dark mb-1">{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</div>
 <div class="text-secondary small mb-1"><i class="fas fa-phone me-1 text-muted"></i> {{ $order['customer']['phone'] }}</div>
 <div class="text-secondary small mb-3"><i class="fas fa-map-marker-alt me-1 text-muted"></i> {{ $order['customer']['address'] }}, {{ $order['customer']['city'] }}</div>
 <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill">
 Payment: {{ $order['payment_method'] }}
 </span>
 </div>
 </div>
 </div>

 <!-- ITEMIZED RECEIPT SUMMARY -->
 <div class="deen-frame p-4 p-md-5 bg-white shadow-sm">
 <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
 <span class="material-symbols-outlined text-primary fs-5">shopping_cart</span>
 <h4 class="h6 fw-bold text-dark mb-0">Order Items Summary</h4>
 </div>

 <div class="table-responsive mb-4">
 <table class="table align-middle">
 <thead class="table-light text-uppercase small text-secondary" style="font-size: 0.70rem; letter-spacing: 0.05em;">
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
 <td>
 <div class="fw-semibold text-dark">{{ $item['name'] }}</div>
 </td>
 <td class="text-center">{{ $item['qty'] }}</td>
 <td class="text-end text-secondary">৳{{ number_format($item['price'], 2) }}</td>
 <td class="text-end fw-bold text-dark font-display">৳{{ number_format($item['price'] * $item['qty'], 2) }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <div class="d-flex justify-content-between align-items-center border-top pt-3">
 <a href="{{ route('store.order.success', $order['order_id']) }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">receipt_long</span>
 <span>View Receipt Invoice</span>
 </a>
 <div class="text-end">
 <span class="text-secondary small">Grand Total:</span>
 <span class="h4 fw-bold text-dark font-display ms-1">৳{{ number_format($order['total'], 2) }}</span>
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
</style>
@endsection
