@extends('layouts.app')

@section('content')
<div class="deen-section-py-lg min-vh-100">
 <div class="container">
 <!-- Header -->
 <div class="mb-5 text-center">
 <span class="deen-vibrant-pill emerald mb-3 d-inline-block">
 <span class="material-symbols-outlined align-middle fs-6 me-1">verified_user</span> 256-Bit Encrypted Retail Checkout
 </span>
 <h1 class="deen-title-lg text-dark mb-2"><span class="deen-gradient-text">Complete Your Wardrobe Order</span></h1>
 <p class="text-secondary small">Direct dispatch from Deen Commerce &bull; 7-Day Hassle-Free Returns</p>
 </div>

 <!-- 4-STEP CHECKOUT PROGRESS BAR -->
 <div class="deen-checkout-steps max-width-md mx-auto mb-5 d-flex justify-content-center align-items-center gap-4">
 <div class="deen-step-item completed" title="1. Cart">
 <span class="material-symbols-outlined">check</span>
 </div>
 <div class="deen-step-item active" title="2. Delivery Address">2</div>
 <div class="deen-step-item" title="3. Payment Method">3</div>
 <div class="deen-step-item" title="4. Order Receipt">4</div>
 </div>

 <form method="POST" action="{{ route('store.checkout.process') }}" id="checkoutForm" onsubmit="prepareCartSubmission()">
 @csrf
 <input type="hidden" name="cart_data" id="cartDataInput">

 <div class="row g-5">
 <!-- Left Column: Shipping Address & Payment Selection -->
 <div class="col-lg-7">
 <!-- Shipping Address Card -->
 <div class="deen-frame p-4 p-md-5 mb-4">
 <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
 <span class="material-symbols-outlined text-primary fs-3">local_shipping</span>
 <h2 class="deen-title-sm mb-0">1. Client Shipping & Logistics</h2>
 </div>

 <div class="row g-3">
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">First Name *</label>
 <input type="text" name="first_name" class="form-control deen-input" placeholder="e.g. Tanvir" required>
 </div>
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">Last Name *</label>
 <input type="text" name="last_name" class="form-control deen-input" placeholder="e.g. Ahmed" required>
 </div>
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">Email Address *</label>
 <input type="email" name="email" class="form-control deen-input" placeholder="tanvir@example.com" required>
 </div>
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">Mobile Phone Number *</label>
 <input type="text" name="phone" class="form-control deen-input" placeholder="01711-000000" required>
 </div>
 <div class="col-12">
 <label class="form-label small fw-semibold text-secondary">Full Street Address *</label>
 <input type="text" name="address" class="form-control deen-input" placeholder="House 12, Road 5, Block B, Mirpur, Dhaka" required>
 </div>
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">City / District *</label>
 <input type="text" name="city" class="form-control deen-input" placeholder="Dhaka" required>
 </div>
 <div class="col-md-6">
 <label class="form-label small fw-semibold text-secondary">Delivery Location & Speed *</label>
 <select id="shippingZoneSelect" onchange="recalculateTotals()" class="form-select deen-input">
 <option value="60" selected>Inside Dhaka City (৳60 &bull; 1-2 Days)</option>
 <option value="120">Outside Dhaka Suburbs (৳120 &bull; 2-4 Days)</option>
 <option value="150">Express 24h Delivery (৳150 &bull; Same Day Dispatch)</option>
 </select>
 </div>
 </div>
 </div>

 <!-- Payment Selector Card -->
 <div class="deen-frame p-4 p-md-5">
 <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
 <span class="material-symbols-outlined text-primary fs-3">payments</span>
 <h2 class="deen-title-sm mb-0">2. Select Payment Settlement</h2>
 </div>

 <div class="d-flex flex-column gap-3 mb-4" role="radiogroup" aria-label="Select payment method">
 <!-- bKash -->
 <label class="deen-payment-card active p-3 d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'bkash')" role="radio" aria-checked="true" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' ')selectPayment(this, 'bkash')">
 <input type="radio" name="payment_method" value="bkash" checked class="d-none">
 <div class="deen-pastel-pill sand px-3 py-2 fw-bold">bKash</div>
 <div class="flex-grow-1">
 <div class="fw-semibold text-dark">bKash Instant Mobile Banking</div>
 <div class="small text-secondary">Pay securely via bKash Merchant Wallet (#01711-000000)</div>
 </div>
 <span class="material-symbols-outlined text-primary fs-4 check-icon">check_circle</span>
 </label>

 <!-- Nagad -->
 <label class="deen-payment-card p-3 d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'nagad')" role="radio" aria-checked="false" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' ')selectPayment(this, 'nagad')">
 <input type="radio" name="payment_method" value="nagad" class="d-none">
 <div class="deen-pastel-pill sand px-3 py-2 fw-bold">Nagad</div>
 <div class="flex-grow-1">
 <div class="fw-semibold text-dark">Nagad Mobile Payment</div>
 <div class="small text-secondary">Pay via Nagad Merchant Wallet (#01811-000000)</div>
 </div>
 <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
 </label>

 <!-- Cash on Delivery -->
 <label class="deen-payment-card p-3 d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'cod')" role="radio" aria-checked="false" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' ')selectPayment(this, 'cod')">
 <input type="radio" name="payment_method" value="cod" class="d-none">
 <div class="deen-pastel-pill sage px-3 py-2 fw-bold">COD</div>
 <div class="flex-grow-1">
 <div class="fw-semibold text-dark">Cash on Delivery (COD)</div>
 <div class="small text-secondary">Pay cash upon courier arrival and parcel inspection</div>
 </div>
 <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
 </label>

 <!-- Credit / Debit Card -->
 <label class="deen-payment-card p-3 d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'card')" role="radio" aria-checked="false" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' ')selectPayment(this, 'card')">
 <input type="radio" name="payment_method" value="card" class="d-none">
 <div class="deen-pastel-pill azure px-3 py-2 fw-bold">Card</div>
 <div class="flex-grow-1">
 <div class="fw-semibold text-dark">Debit / Credit Card (SSLCommerz)</div>
 <div class="small text-secondary">Visa, Mastercard, American Express & Internet Banking</div>
 </div>
 <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
 </label>
 </div>

 <!-- TrxID Input Container for Mobile Banking -->
 <div id="trxIdContainer" class="p-3 deen-frame deen-pastel-sand mb-4">
 <label class="form-label small fw-semibold text-dark mb-1">
 <i class="fas fa-info-circle me-1"></i> bKash / Nagad Transaction ID (Optional)
 </label>
 <input type="text" name="trx_id" class="form-control deen-input" placeholder="e.g. 9J7821X90B">
 <div class="small text-secondary mt-1">Please send payment to Merchant Number <strong class="text-dark">01711-000000</strong> and paste TrxID above.</div>
 </div>

 <button type="submit" class="btn-deen-vibrant w-100 justify-content-center py-3 fs-6">
 <span class="material-symbols-outlined fs-5 me-2">lock</span> Confirm & Place Wardrobe Order
 </button>
 </div>
 </div>

 <!-- Right Column: Interactive Order Items Summary Sidebar -->
 <div class="col-lg-5">
 <div class="deen-frame deen-pastel-linen p-4 p-md-5 position-sticky" style="top: 100px;">
 <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
 <div class="d-flex align-items-center gap-2">
 <span class="material-symbols-outlined text-dark fs-4">shopping_cart</span>
 <h3 class="deen-title-sm mb-0">Order Summary</h3>
 </div>
 <span id="checkoutItemCountBadge" class="deen-pastel-pill azure">0 Items</span>
 </div>

 <!-- Item List Container -->
 <div id="checkoutSummaryList" class="mb-4">
 <!-- Items dynamically rendered by JS -->
 </div>

 <!-- Coupon Promo Input -->
 <div class="mb-4 pt-3 border-top">
 <label class="form-label small fw-semibold text-secondary">Promotional Privilege Code</label>
 <div class="input-group">
 <input type="text" id="couponInput" class="form-control deen-input text-uppercase" placeholder="e.g. DEEN2026">
 <button type="button" onclick="applyCouponCode()" class="btn-deen-outline btn-sm px-3">Apply</button>
 </div>
 <div id="couponStatus" class="small mt-1"></div>
 </div>

 <!-- Pricing Breakdown Grid -->
 <div class="border-top pt-3">
 <div class="d-flex justify-content-between text-secondary small mb-2">
 <span>Cart Subtotal:</span>
 <span id="summarySubtotal" class="fw-semibold text-dark">৳0.00</span>
 </div>
 <div class="d-flex justify-content-between text-secondary small mb-2">
 <span>Logistics Fee:</span>
 <span id="summaryShippingFee" class="text-dark fw-semibold">৳60.00</span>
 </div>
 <div id="couponDiscountRow" class="d-flex justify-content-between text-success small mb-2 d-none">
 <span>Privilege Discount:</span>
 <span id="summaryDiscount" class="fw-semibold">-৳0.00</span>
 </div>
 <div class="d-flex justify-content-between fw-bold text-dark fs-4 border-top pt-3 mt-3">
 <span>Grand Total:</span>
 <span id="summaryGrandTotal" class="font-display">৳0.00</span>
 </div>
 </div>

 <!-- Security Perks -->
 <div class="mt-4 p-3 deen-frame deen-pastel-sage text-center small text-secondary">
 <span class="material-symbols-outlined text-success align-middle fs-5 me-1">verified_user</span>
 100% Authentic Quality Guaranteed by Deen Commerce
 </div>
 </div>
 </div>
 </div>
 </form>
 </div>
</div>

<script>
let cartState = [];
let appliedDiscount = 0;

document.addEventListener('DOMContentLoaded', () => {
 loadCheckoutSummary();
});

function loadCheckoutSummary() {
 let saved = localStorage.getItem('deen_cart');
 if (saved) {
 try { cartState = JSON.parse(saved); } catch(e) { cartState = []; }
 }

 if (!cartState || cartState.length === 0) {
 // Sample fallback checkout item
 cartState = [
 {
 id: 202567,
 name: 'Heavyweight Washed Denim Trousers',
 price: 2490.00,
 qty: 1,
 img: 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg'
 }
 ];
 }

 renderCheckoutList();
}

function renderCheckoutList() {
 const container = document.getElementById('checkoutSummaryList');
 const countBadge = document.getElementById('checkoutItemCountBadge');

 let totalItems = 0;
 let subtotal = 0;

 if (cartState.length === 0) {
 container.innerHTML = '<div class="text-center py-4 text-secondary"><span class="material-symbols-outlined fs-1 mb-2 opacity-50">shopping_cart</span><p class="small mb-0">Your cart is currently empty.</p></div>';
 countBadge.innerText = '0 Items';
 subtotal = 0;
 } else {
 let html = '<div class="d-flex flex-column gap-3">';
 cartState.forEach((item, idx) => {
 const itemSub = item.price * item.qty;
 subtotal += itemSub;
 totalItems += item.qty;

 const img = item.img || 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg';

 html += `
 <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-white border">
 <div class="d-flex align-items-center gap-3">
 <img src="${img}" class="rounded-2 border">
 <div>
 <div class="fw-semibold text-dark small text-truncate">${item.name}</div>
 <div class="text-secondary small">৳${item.price.toFixed(2)}</div>
 <div class="d-flex align-items-center gap-1 mt-1">
 <button type="button" onclick="updateQty(${idx}, -1)" class="btn btn-sm btn-outline-secondary py-0 px-2">-</button>
 <span class="small px-2 fw-semibold text-dark">${item.qty}</span>
 <button type="button" onclick="updateQty(${idx}, 1)" class="btn btn-sm btn-outline-secondary py-0 px-2">+</button>
 <button type="button" onclick="removeItem(${idx})" class="btn btn-sm text-danger ms-2 p-0"><i class="fas fa-trash-alt small"></i></button>
 </div>
 </div>
 </div>
 <div class="fw-semibold text-dark small">৳${itemSub.toFixed(2)}</div>
 </div>
 `;
 });
 html += '</div>';
 container.innerHTML = html;
 countBadge.innerText = totalItems + (totalItems === 1 ? ' Item' : ' Items');
 }

 recalculateTotals(subtotal);
}

function updateQty(index, change) {
 if (cartState[index]) {
 cartState[index].qty += change;
 if (cartState[index].qty <= 0) {
 cartState.splice(index, 1);
 }
 localStorage.setItem('deen_cart', JSON.stringify(cartState));
 renderCheckoutList();
 }
}

function removeItem(index) {
 if (cartState[index]) {
 cartState.splice(index, 1);
 localStorage.setItem('deen_cart', JSON.stringify(cartState));
 renderCheckoutList();
 }
}

function recalculateTotals(overrideSubtotal = null) {
 let subtotal = overrideSubtotal;
 if (subtotal === null) {
 subtotal = cartState.reduce((acc, item) => acc + (item.price * item.qty), 0);
 }

 const shippingFee = parseFloat(document.getElementById('shippingZoneSelect').value || 60);
 const grandTotal = Math.max(0, subtotal + shippingFee - appliedDiscount);

 document.getElementById('summarySubtotal').innerText = '৳' + subtotal.toFixed(2);
 document.getElementById('summaryShippingFee').innerText = '৳' + shippingFee.toFixed(2);

 if (appliedDiscount > 0) {
 document.getElementById('couponDiscountRow').classList.remove('d-none');
 document.getElementById('summaryDiscount').innerText = '-৳' + appliedDiscount.toFixed(2);
 } else {
 document.getElementById('couponDiscountRow').classList.add('d-none');
 }

 document.getElementById('summaryGrandTotal').innerText = '৳' + grandTotal.toFixed(2);
}

function applyCouponCode() {
 const code = (document.getElementById('couponInput').value || '').trim().toUpperCase();
 const statusEl = document.getElementById('couponStatus');
 const subtotal = cartState.reduce((acc, item) => acc + (item.price * item.qty), 0);

 if (code === 'DEEN2026') {
 appliedDiscount = subtotal * 0.20;
 statusEl.className = 'small mt-1 text-success fw-bold';
 statusEl.innerText = `✓ Code DEEN2026 Applied (20% OFF: -৳${appliedDiscount.toFixed(2)})`;
 } else if (code === 'DEEN10') {
 appliedDiscount = 100;
 statusEl.className = 'small mt-1 text-success fw-bold';
 statusEl.innerText = '✓ Code DEEN10 Applied (৳100 OFF)';
 } else if (code === 'FREESHIP') {
 appliedDiscount = parseFloat(document.getElementById('shippingZoneSelect').value || 60);
 statusEl.className = 'small mt-1 text-success fw-bold';
 statusEl.innerText = '✓ Code FREESHIP Applied (Free Shipping)';
 } else {
 appliedDiscount = 0;
 statusEl.className = 'small mt-1 text-danger fw-bold';
 statusEl.innerText = '✗ Invalid Privilege Code. Try DEEN2026 or DEEN10.';
 }

 renderCheckoutList();
}

function selectPayment(labelEl, method) {
 document.querySelectorAll('.deen-payment-card').forEach(card => {
 card.classList.remove('active');
 const icon = card.querySelector('.check-icon');
 if (icon) {
 icon.innerText = 'radio_button_unchecked';
 icon.className = 'material-symbols-outlined text-secondary fs-4 check-icon';
 }
 });

 labelEl.classList.add('active');
 labelEl.querySelector('input[type="radio"]').checked = true;

 const icon = labelEl.querySelector('.check-icon');
 if (icon) {
 icon.innerText = 'check_circle';
 icon.className = 'material-symbols-outlined text-primary fs-4 check-icon';
 }

 const trxContainer = document.getElementById('trxIdContainer');
 if (method === 'bkash' || method === 'nagad') {
 trxContainer.classList.remove('d-none');
 } else {
 trxContainer.classList.add('d-none');
 }
}

function prepareCartSubmission() {
 document.getElementById('cartDataInput').value = JSON.stringify(cartState);
 localStorage.removeItem('deen_cart');
 localStorage.removeItem('deen_checkout_form');
}

// Auto-save form inputs to localStorage for seamless mobile experience
document.addEventListener('input', (e) => {
 if (e.target.closest('#checkoutForm')) {
 const formData = {
 first_name: document.querySelector('[name="first_name"]')?.value || '',
 last_name: document.querySelector('[name="last_name"]')?.value || '',
 email: document.querySelector('[name="email"]')?.value || '',
 phone: document.querySelector('[name="phone"]')?.value || '',
 address: document.querySelector('[name="address"]')?.value || '',
 city: document.querySelector('[name="city"]')?.value || '',
 };
 localStorage.setItem('deen_checkout_form', JSON.stringify(formData));
 }
});

document.addEventListener('DOMContentLoaded', () => {
 const savedForm = localStorage.getItem('deen_checkout_form');
 if (savedForm) {
 try {
 const data = JSON.parse(savedForm);
 if (data.first_name) document.querySelector('[name="first_name"]').value = data.first_name;
 if (data.last_name) document.querySelector('[name="last_name"]').value = data.last_name;
 if (data.email) document.querySelector('[name="email"]').value = data.email;
 if (data.phone) document.querySelector('[name="phone"]').value = data.phone;
 if (data.address) document.querySelector('[name="address"]').value = data.address;
 if (data.city) document.querySelector('[name="city"]').value = data.city;
 } catch(e) {}
 }
});
</script>
@endsection

