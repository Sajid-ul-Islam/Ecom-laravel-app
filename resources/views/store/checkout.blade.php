@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="mb-5 text-center">
            <span class="badge bg-danger text-white mb-2 px-3 py-2 rounded-pill fw-bold"><i class="fas fa-lock me-1"></i> SECURE CHECKOUT</span>
            <h1 class="fw-bold text-dark display-5">Complete Your Order</h1>
            <p class="text-muted">Enter your shipping details and select your preferred payment method</p>
        </div>

        <form method="POST" action="{{ route('store.checkout.process') }}" id="checkoutForm" onsubmit="prepareCartData()">
            @csrf
            <input type="hidden" name="cart_data" id="cartDataInput">

            <div class="row g-4">
                <!-- Customer & Payment Details -->
                <div class="col-lg-7">
                    <!-- Shipping Address Card -->
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-4">
                        <h4 class="fw-bold text-dark mb-4"><i class="fas fa-truck text-primary me-2"></i> Shipping Address</h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">First Name *</label>
                                <input type="text" name="first_name" class="form-control rounded-3" placeholder="e.g. Tanvir" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Last Name *</label>
                                <input type="text" name="last_name" class="form-control rounded-3" placeholder="e.g. Ahmed" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Email Address *</label>
                                <input type="email" name="email" class="form-control rounded-3" placeholder="tanvir@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Phone Number *</label>
                                <input type="text" name="phone" class="form-control rounded-3" placeholder="+880 1711-000000" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Street Address *</label>
                                <input type="text" name="address" class="form-control rounded-3" placeholder="House/Road/Block Details" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">City / District *</label>
                                <input type="text" name="city" class="form-control rounded-3" placeholder="Dhaka" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selector -->
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                        <h4 class="fw-bold text-dark mb-4"><i class="fas fa-credit-card text-primary me-2"></i> Select Payment Method</h4>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <!-- bKash -->
                            <label class="deen-payment-card active" onclick="selectPayment(this, 'bkash')">
                                <input type="radio" name="payment_method" value="bkash" checked class="d-none">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">bK</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">bKash Mobile Banking</div>
                                    <div class="small text-muted">Pay instantly via bKash Wallet</div>
                                </div>
                                <i class="fas fa-check-circle text-danger fs-5"></i>
                            </label>

                            <!-- Nagad -->
                            <label class="deen-payment-card" onclick="selectPayment(this, 'nagad')">
                                <input type="radio" name="payment_method" value="nagad" class="d-none">
                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">NG</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Nagad Mobile Payment</div>
                                    <div class="small text-muted">Pay securely using Nagad</div>
                                </div>
                                <i class="fas fa-circle text-muted fs-5"></i>
                            </label>

                            <!-- Cash on Delivery -->
                            <label class="deen-payment-card" onclick="selectPayment(this, 'cod')">
                                <input type="radio" name="payment_method" value="cod" class="d-none">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;"><i class="fas fa-money-bill-wave"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Cash on Delivery (COD)</div>
                                    <div class="small text-muted">Pay cash when package arrives at your doorstep</div>
                                </div>
                                <i class="fas fa-circle text-muted fs-5"></i>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow">
                            Place Order Now <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Order Item Summary Sidebar -->
                <div class="col-lg-5">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border position-sticky" style="top: 100px;">
                        <h4 class="fw-bold text-dark mb-4"><i class="fas fa-shopping-bag text-primary me-2"></i> Order Summary</h4>

                        <div id="checkoutSummaryItems" class="mb-4">
                            <!-- Items injected via JS -->
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between text-muted mb-2">
                                <span>Subtotal:</span>
                                <span id="summarySubtotal">৳0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted mb-3">
                                <span>Shipping Fee:</span>
                                <span class="text-success fw-bold">FREE</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold text-dark fs-4 border-top pt-2">
                                <span>Grand Total:</span>
                                <span id="summaryGrandTotal" class="text-danger">৳0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadCheckoutSummary();
});

function selectPayment(element, method) {
    document.querySelectorAll('.deen-payment-card').forEach(c => {
        c.classList.remove('active');
        const icon = c.querySelector('.fa-check-circle, .fa-circle');
        if (icon) icon.className = 'fas fa-circle text-muted fs-5';
    });
    element.classList.add('active');
    element.querySelector('input[type="radio"]').checked = true;
    element.querySelector('.fa-circle').className = 'fas fa-check-circle text-danger fs-5';
}

function loadCheckoutSummary() {
    let cart = JSON.parse(localStorage.getItem('deen_cart') || '[]');
    const container = document.getElementById('checkoutSummaryItems');
    const subtotalEl = document.getElementById('summarySubtotal');
    const totalEl = document.getElementById('summaryGrandTotal');

    if (cart.length === 0) {
        // Mock default sample items for demonstration
        cart = [
            { id: 202567, name: 'High-End Raw Washed Jeans', price: 2490.00, qty: 1 }
        ];
    }

    let subtotal = 0;
    let html = '<div class="d-flex flex-column gap-3">';
    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        html += `
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                <div>
                    <div class="fw-bold text-dark">${item.name}</div>
                    <div class="small text-muted">৳${item.price.toFixed(2)} × ${item.qty}</div>
                </div>
                <div class="fw-bold text-dark">৳${itemTotal.toFixed(2)}</div>
            </div>
        `;
    });
    html += '</div>';

    container.innerHTML = html;
    subtotalEl.innerText = '৳' + subtotal.toFixed(2);
    totalEl.innerText = '৳' + subtotal.toFixed(2);
}

function prepareCartData() {
    let cart = JSON.parse(localStorage.getItem('deen_cart') || '[]');
    if (cart.length === 0) {
        cart = [
            { id: 202567, name: 'High-End Raw Washed Jeans', price: 2490.00, qty: 1 }
        ];
    }
    document.getElementById('cartDataInput').value = JSON.stringify(cart);
}
</script>
@endsection
