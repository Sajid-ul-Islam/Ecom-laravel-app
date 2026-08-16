@extends('layouts.app')

@push('meta')
<meta name="description" content="Your Deen Commerce order has been confirmed. Track your denim and apparel order status.">
@endpush

@section('content')
<div class="deen-section-py-lg min-vh-100">
 <div class="container">

 <!-- Success Header -->
 <div class="text-center mb-5">
 <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm rounded-circle" style="width: 80px; height: 80px; background: #ecfdf5; border: 2px solid #a7f3d0;">
 <span class="material-symbols-outlined fs-1 text-success">check_circle</span>
 </div>
 <h1 class="deen-title-lg text-dark mb-2"><span class="deen-gradient-text">Order Confirmed!</span></h1>
 <p class="text-secondary small mb-3">Thank you for shopping with Deen Commerce. We'll get your order ready for dispatch.</p>
 <span class="deen-vibrant-pill indigo fs-6">Order #{{ $order['order_id'] }}</span>
 </div>

 <!-- Invoice Receipt Card -->
 <div class="deen-frame p-4 p-md-5 mb-4">
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom pb-4 mb-4">
 <div>
 <h3 class="deen-title-sm mb-1">Order Receipt</h3>
 <div class="small text-secondary">{{ $order['created_at'] }}</div>
 </div>
 <div class="mt-3 mt-md-0">
 <span class="deen-pastel-pill sage">
 <i class="fas fa-clock me-1"></i> Payment: {{ strtoupper($order['customer']['payment_method'] ?? 'COD') }}
 </span>
 </div>
 </div>

 <!-- Customer Details -->
 <div class="row g-4 mb-4">
 <div class="col-md-6">
 <div class="small text-secondary text-uppercase fw-semibold mb-1">Delivered to:</div>
 <div class="fw-semibold text-dark">{{ $order['customer']['first_name'] ?? 'Valued' }} {{ $order['customer']['last_name'] ?? 'Shopper' }}</div>
 <div class="small text-secondary">{{ $order['customer']['email'] ?? '' }}</div>
 <div class="small text-secondary">{{ $order['customer']['phone'] ?? '' }}</div>
 </div>
 <div class="col-md-6">
 <div class="small text-secondary text-uppercase fw-semibold mb-1">Shipping address:</div>
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
 <th>Item</th>
 <th class="text-center">Qty</th>
 <th class="text-end">Price</th>
 <th class="text-end">Total</th>
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
 <span>Delivery:</span>
 <span class="text-dark fw-semibold">FREE</span>
 </div>
 <div class="d-flex justify-content-between w-100 border-top mt-3 pt-3 fs-5 fw-bold text-dark">
 <span>Grand Total:</span>
 <span class="font-display">৳{{ number_format($order['total'], 2) }}</span>
 </div>
 </div>
 </div>

 <!-- What Happens Next -->
 <div class="deen-frame p-4 p-md-5 mb-4">
 <h3 class="deen-title-sm mb-4">What happens next?</h3>
 <div class="row g-4">
 <div class="col-md-4 text-center">
 <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 52px; height: 52px; background: #dbeafe;">
 <span class="material-symbols-outlined text-primary">inventory_2</span>
 </div>
 <div class="fw-semibold text-dark small mb-1">Order Packed</div>
 <div class="text-secondary" style="font-size: 0.8rem;">We'll prepare your garments within 24 hours</div>
 </div>
 <div class="col-md-4 text-center">
 <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 52px; height: 52px; background: #fef3c7;">
 <span class="material-symbols-outlined text-warning">local_shipping</span>
 </div>
 <div class="fw-semibold text-dark small mb-1">Dispatched</div>
 <div class="text-secondary" style="font-size: 0.8rem;">Courier picks up your package same day</div>
 </div>
 <div class="col-md-4 text-center">
 <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 52px; height: 52px; background: #d1fae5;">
 <span class="material-symbols-outlined text-success">where_to_vote</span>
 </div>
 <div class="fw-semibold text-dark small mb-1">Delivered</div>
 <div class="text-secondary" style="font-size: 0.8rem;">Arrives within 1–4 days depending on your zone</div>
 </div>
 </div>
 </div>

 <!-- Next Order Discount Teaser -->
 <div class="deen-frame deen-pastel-sand p-4 mb-4 d-flex flex-column flex-md-row align-items-center gap-4 justify-content-between">
 <div>
 <span class="deen-pastel-pill sand mb-2">Exclusive Reward</span>
 <h4 class="fw-bold text-dark mb-1">Save ৳200 on your next order</h4>
 <p class="text-secondary small mb-0">Use code <strong class="text-dark font-monospace">DEEN2ND</strong> at checkout — valid for 30 days</p>
 </div>
 <button type="button" onclick="navigator.clipboard.writeText('DEEN2ND').then(() => this.textContent = 'Copied!')" class="btn-deen-outline flex-shrink-0">
 <i class="fas fa-copy me-1"></i> Copy Code
 </button>
 </div>

 <!-- Social Share -->
 <div class="deen-frame p-4 mb-5 text-center">
 <h4 class="fw-bold text-dark mb-2">Show off your new DEEN look</h4>
 <p class="text-secondary small mb-4">Share your order on social media and tag <strong>@deencommerce</strong></p>
 <div class="d-flex justify-content-center gap-3 flex-wrap">
 <a href="https://wa.me/?text={{ urlencode('I just ordered from Deen Commerce! Check out their premium denim collection 👕 https://deencommerce.com') }}" target="_blank" class="btn-deen-outline">
 <i class="fab fa-whatsapp me-2 text-success"></i> Share on WhatsApp
 </a>
 <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/')) }}" target="_blank" class="btn-deen-outline">
 <i class="fab fa-facebook-f me-2 text-primary"></i> Share on Facebook
 </a>
 </div>
 </div>

 <!-- Action Controls -->
 <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
 <button onclick="window.print()" class="btn-deen-outline justify-content-center">
 <i class="fas fa-print me-2"></i> Print Receipt
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
