@extends('layouts.app')

@section('content')
<div class="py-5 denim-vibe-bg min-vh-100">
    <div class="container">
        <!-- Header -->
        <div class="mb-5 text-center">
            <span class="deen-leather-patch mb-3 d-inline-block">
                <span class="material-symbols-outlined align-middle fs-6 me-1 text-warning">lock</span> SECURE RETAIL CHECKOUT
            </span>
            <h1 class="deen-hero-heading text-white mb-2">Complete Your Fashion Order</h1>
            <p class="text-white-50">Enter your shipping details below &bull; Free returns within 7 days</p>
        </div>

        <form method="POST" action="{{ route('store.checkout.process') }}" id="checkoutForm" onsubmit="prepareCartSubmission()">
            @csrf
            <input type="hidden" name="cart_data" id="cartDataInput">

            <div class="row g-4">
                <!-- Left Column: Shipping Address & Payment Selection -->
                <div class="col-lg-7">
                    <!-- Shipping Address Card -->
                    <div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-lg border border-secondary mb-4">
                        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-secondary">
                            <span class="material-symbols-outlined text-warning fs-3">local_shipping</span>
                            <h4 class="fw-bold mb-0 text-white">1. Shipping & Delivery Address</h4>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">First Name *</label>
                                <input type="text" name="first_name" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="Tanvir" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">Last Name *</label>
                                <input type="text" name="last_name" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="Ahmed" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="tanvir@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">Mobile Phone Number *</label>
                                <input type="text" name="phone" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="01711-000000" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white-50 small fw-bold">Full Street Address *</label>
                                <input type="text" name="address" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="House 12, Road 5, Block B, Mirpur 10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">City / District *</label>
                                <input type="text" name="city" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="Dhaka" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-bold">Delivery Location & Speed *</label>
                                <select id="shippingZoneSelect" onchange="recalculateTotals()" class="form-select bg-dark text-white border-secondary rounded-3">
                                    <option value="60" selected>Inside Dhaka City (৳60 &bull; 1-2 Days)</option>
                                    <option value="120">Outside Dhaka Suburbs (৳120 &bull; 2-4 Days)</option>
                                    <option value="150">Express 24h Delivery (৳150 &bull; Guaranteed Same Day)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Selector Card -->
                    <div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-lg border border-secondary">
                        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-secondary">
                            <span class="material-symbols-outlined text-warning fs-3">payments</span>
                            <h4 class="fw-bold mb-0 text-white">2. Select Payment Gateway</h4>
                        </div>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <!-- bKash -->
                            <label class="deen-payment-card active p-3 rounded-4 border border-secondary bg-dark d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'bkash')">
                                <input type="radio" name="payment_method" value="bkash" checked class="d-none">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">bK</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-white">bKash Instant Mobile Banking</div>
                                    <div class="small text-white-50">Pay via bKash Merchant Wallet (#01711-000000)</div>
                                </div>
                                <span class="material-symbols-outlined text-danger fs-4 check-icon">check_circle</span>
                            </label>

                            <!-- Nagad -->
                            <label class="deen-payment-card p-3 rounded-4 border border-secondary bg-dark d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'nagad')">
                                <input type="radio" name="payment_method" value="nagad" class="d-none">
                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">NG</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-white">Nagad Mobile Payment</div>
                                    <div class="small text-white-50">Pay securely via Nagad Wallet (#01811-000000)</div>
                                </div>
                                <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
                            </label>

                            <!-- Cash on Delivery -->
                            <label class="deen-payment-card p-3 rounded-4 border border-secondary bg-dark d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'cod')">
                                <input type="radio" name="payment_method" value="cod" class="d-none">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-white">Cash on Delivery (COD)</div>
                                    <div class="small text-white-50">Pay cash when courier arrives at your doorstep</div>
                                </div>
                                <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
                            </label>

                            <!-- Credit / Debit Card -->
                            <label class="deen-payment-card p-3 rounded-4 border border-secondary bg-dark d-flex align-items-center gap-3 cursor-pointer" onclick="selectPayment(this, 'card')">
                                <input type="radio" name="payment_method" value="card" class="d-none">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                                    <span class="material-symbols-outlined">credit_card</span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-white">Debit / Credit Card (SSLCommerz)</div>
                                    <div class="small text-white-50">Visa, Mastercard, Amex, Internet Banking</div>
                                </div>
                                <span class="material-symbols-outlined text-secondary fs-4 check-icon">radio_button_unchecked</span>
                            </label>
                        </div>

                        <!-- TrxID Input Container for Mobile Banking -->
                        <div id="trxIdContainer" class="p-3 bg-secondary bg-opacity-20 border border-secondary rounded-3 mb-4">
                            <label class="form-label text-warning small fw-bold mb-1">
                                <span class="material-symbols-outlined align-middle fs-6 me-1">info</span> bKash / Nagad Transaction ID (Optional)
                            </label>
                            <input type="text" name="trx_id" class="form-control bg-dark text-white border-secondary rounded-3" placeholder="e.g. 9J7821X90B">
                            <div class="form-text text-white-50 small mt-1">Please send money to Merchant Number <strong class="text-warning">01711-000000</strong> and paste TrxID above.</div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow py-3 text-dark">
                            <span class="material-symbols-outlined align-middle me-2">lock</span> Complete & Place Order
                        </button>
                    </div>
                </div>

                <!-- Right Column: Interactive Order Items Summary Sidebar -->
                <div class="col-lg-5">
                    <div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-lg border border-secondary position-sticky" style="top: 100px;">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-warning fs-3">shopping_bag</span>
                                <h4 class="fw-bold mb-0 text-white">Order Summary</h4>
                            </div>
                            <span id="checkoutItemCountBadge" class="badge bg-warning text-dark rounded-pill fw-bold">0 Items</span>
                        </div>

                        <!-- Item List Container -->
                        <div id="checkoutSummaryList" class="mb-4">
                            <!-- Items dynamically rendered by JS -->
                        </div>

                        <!-- Coupon Promo Input -->
                        <div class="mb-4 pt-3 border-top border-secondary">
                            <label class="form-label text-white-50 small fw-bold">Have a Promo Coupon Code?</label>
                            <div class="input-group">
                                <input type="text" id="couponInput" class="form-control bg-dark text-white border-secondary text-uppercase" placeholder="e.g. DEEN2026">
                                <button type="button" onclick="applyCouponCode()" class="btn btn-outline-warning fw-bold">Apply</button>
                            </div>
                            <div id="couponStatus" class="small mt-1"></div>
                        </div>

                        <!-- Pricing Breakdown Grid -->
                        <div class="border-top border-secondary pt-3">
                            <div class="d-flex justify-content-between text-white-50 mb-2">
                                <span>Cart Subtotal:</span>
                                <span id="summarySubtotal" class="fw-bold text-white">৳0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-white-50 mb-2">
                                <span>Shipping Fee:</span>
                                <span id="summaryShippingFee" class="text-success fw-bold">৳60.00</span>
                            </div>
                            <div id="couponDiscountRow" class="d-flex justify-content-between text-warning mb-2 d-none">
                                <span>Promo Discount:</span>
                                <span id="summaryDiscount">-৳0.00</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold text-white fs-4 border-top border-secondary pt-3 mt-2">
                                <span>Grand Total:</span>
                                <span id="summaryGrandTotal" class="text-warning">৳0.00</span>
                            </div>
                        </div>

                        <!-- Security Perks -->
                        <div class="mt-4 p-3 bg-secondary bg-opacity-10 border border-secondary rounded-3 text-center text-white-50 small">
                            <span class="material-symbols-outlined text-success align-middle fs-5 me-1">verified_user</span>
                            100% Guaranteed Official Deen Commerce Quality
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
                name: 'High-End Raw Washed Slim Fit Jeans',
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
        container.innerHTML = '<div class="text-center py-4 text-white-50"><span class="material-symbols-outlined fs-1 mb-2 opacity-50">shopping_bag</span><p>Your shopping bag is empty.</p></div>';
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
                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-secondary bg-opacity-20 border border-secondary">
                    <div class="d-flex align-items-center gap-3">
                        <img src="${img}" class="rounded-3 border border-secondary" style="width: 54px; height: 54px; object-fit: cover;">
                        <div>
                            <div class="fw-bold text-white small text-truncate" style="max-width: 170px;">${item.name}</div>
                            <div class="text-warning fw-bold small">৳${item.price.toFixed(2)}</div>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <button type="button" onclick="updateQty(${idx}, -1)" class="btn btn-sm btn-outline-secondary py-0 px-2 text-white">-</button>
                                <span class="badge bg-dark px-2">${item.qty}</span>
                                <button type="button" onclick="updateQty(${idx}, 1)" class="btn btn-sm btn-outline-secondary py-0 px-2 text-white">+</button>
                                <button type="button" onclick="removeItem(${idx})" class="btn btn-sm text-danger ms-2 p-0"><span class="material-symbols-outlined fs-6 align-middle">delete</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="fw-bold text-white fs-6">৳${itemSub.toFixed(2)}</div>
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

    if (code === 'DEEN2026') {
        appliedDiscount = 300;
        statusEl.className = 'small mt-1 text-success fw-bold';
        statusEl.innerText = '✓ Promo Code DEEN2026 Applied (৳300 OFF)';
    } else if (code === 'FREESHIP') {
        appliedDiscount = parseFloat(document.getElementById('shippingZoneSelect').value || 60);
        statusEl.className = 'small mt-1 text-success fw-bold';
        statusEl.innerText = '✓ Promo Code FREESHIP Applied (Free Shipping)';
    } else {
        appliedDiscount = 0;
        statusEl.className = 'small mt-1 text-danger fw-bold';
        statusEl.innerText = '✗ Invalid or Expired Promo Code';
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
        icon.className = 'material-symbols-outlined text-warning fs-4 check-icon';
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
}
</script>
@endsection
