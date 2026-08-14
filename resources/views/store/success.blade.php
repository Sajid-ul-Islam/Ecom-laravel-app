@extends('layouts.app')

@section('content')
<div class="deen-section-py-lg min-vh-100">
 <div class="container">
 <!-- Success Confirmation Header -->
 <div class="text-center mb-5">
 <div class="deen-vibrant-pill emerald p-3 d-inline-flex align-items-center justify-content-center mb-3 shadow-sm rounded-circle" style="width: 76px; height: 76px; background: #ecfdf5; border: 2px solid #a7f3d0;">
 <span class="material-symbols-outlined fs-1 text-success">check_circle</span>
 </div>
 <h1 class="deen-title-lg text-dark mb-2"><span class="deen-gradient-text">Order Confirmed Successfully</span></h1>
 <p class="text-secondary small mb-3">Thank you for curating your wardrobe with Deen Commerce. Your order is now in editorial preparation for dispatch.</p>
 <span class="deen-vibrant-pill indigo fs-6">Order Invoice #{{ $order['order_id'] }}</span>
 </div>

 <!-- Invoice Receipt Card -->
 <div class="deen-frame p-4 p-md-5 mb-4">
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom pb-4 mb-4">
 <div>
 <h3 class="deen-title-sm mb-1">Deen Commerce Official Receipt</h3>
 <div class="small text-secondary">Verified Record &bull; {{ $order['created_at'] }}</div>
 </div>
 <div class="mt-3 mt-md-0">
 <span class="deen-pastel-pill sage">
 <i class="fas fa-clock me-1"></i> Payment Settlement: {{ strtoupper($order['customer']['payment_method'] ?? 'COD') }}
 </span>
 </div>
 </div>

 <!-- Customer Details -->
 <div class="row g-4 mb-4">
 <div class="col-md-6">
 <div class="small text-secondary text-uppercase fw-semibold mb-1">Recipient Customer:</div>
 <div class="fw-semibold text-dark">{{ $order['customer']['first_name'] ?? 'Valued' }} {{ $order['customer']['last_name'] ?? 'Shopper' }}</div>
 <div class="small text-secondary">{{ $order['customer']['email'] ?? '' }}</div>
 <div class="small text-secondary">{{ $order['customer']['phone'] ?? '' }}</div>
 </div>
 <div class="col-md-6">
 <div class="small text-secondary text-uppercase fw-semibold mb-1">Dispatch Destination:</div>
 <div class="fw-semibold text-dark">{{ $order['customer']['address'] ?? 'Address Details' }}</div>
 <div class="small text-secondary">{{ $order['customer']['city'] ?? 'Dhaka' }}, Bangladesh</div>
 </div>
 </div>

 <!-- Itemized Table -->
 @if(!empty($order['items']))
 <div class="table-responsive mb-4">
 <table class="table deen-spec-table align-middle">
 <thead>
 <tr>
 <th>Item Description</th>
 <th class="text-center">Qty</th>
 <th class="text-end">Unit Price</th>
 <th class="text-end">Line Total</th>
 </tr>
 </thead>
 <tbody>
 @foreach($order['items'] as $item)
 <tr>
 <td class="fw-semibold text-dark">{{ $item['name'] }}</td>
 <td class="text-center">{{ $item['qty'] }}</td>
 <td class="text-end text-secondary">৳{{ number_format($item['price'], 2) }}</td>
 <td class="text-end fw-semibold text-dark">৳{{ number_format($item['price'] * $item['qty'], 2) }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 @endif

 <!-- Total Calculation -->
 <div class="d-flex flex-column align-items-end border-top pt-3">
 <div class="d-flex justify-content-between w-100 text-secondary small">
 <span>Subtotal:</span>
 <span class="fw-semibold text-dark">৳{{ number_format($order['total'], 2) }}</span>
 </div>
 <div class="d-flex justify-content-between w-100 text-secondary small mt-1">
 <span>Delivery Charge:</span>
 <span class="text-dark fw-semibold">FREE</span>
 </div>
 <div class="d-flex justify-content-between w-100 border-top mt-3 pt-3 fs-5 fw-bold text-dark">
 <span>Grand Total:</span>
 <span class="font-display">৳{{ number_format($order['total'], 2) }}</span>
 </div>
 </div>
 </div>

 <!-- Action Controls -->
 <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
 <button onclick="window.print()" class="btn-deen-outline justify-content-center">
 <i class="fas fa-print me-2"></i> Print Official Receipt
 </button>
 <a href="{{ route('store.index') }}" class="btn-deen-primary justify-content-center">
 <i class="fas fa-store me-2"></i> Continue Shopping
 </a>
 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 // Clear local storage cart upon successful checkout completion
 localStorage.removeItem('deen_cart');
});
</script>
@endsection

