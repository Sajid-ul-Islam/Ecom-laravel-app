@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container" style="max-width: 800px;">
        <!-- Success Confirmation Header -->
        <div class="text-center mb-5">
            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px;">
                <i class="fas fa-check fa-3x"></i>
            </div>
            <h1 class="fw-bold text-dark display-5">Order Placed Successfully!</h1>
            <p class="text-muted">Thank you for shopping with Deen Commerce. Your order has been placed and is being prepared.</p>
            <div class="badge bg-dark px-3 py-2 rounded-pill font-monospace fs-6">Order Invoice #{{ $order['order_id'] }}</div>
        </div>

        <!-- Invoice Receipt Card -->
        <div class="deen-receipt-card p-4 p-md-5 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom pb-4 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Deen Commerce Retail Store</h4>
                    <div class="small text-muted">Official Order Receipt &bull; {{ $order['created_at'] }}</div>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-bold">
                        <i class="fas fa-clock me-1"></i> Payment Pending ({{ strtoupper($order['customer']['payment_method'] ?? 'COD') }})
                    </span>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="small text-muted text-uppercase fw-bold mb-1">Shipping Customer:</div>
                    <div class="fw-bold text-dark">{{ $order['customer']['first_name'] ?? 'Valued' }} {{ $order['customer']['last_name'] ?? 'Customer' }}</div>
                    <div class="small text-muted">{{ $order['customer']['email'] ?? '' }}</div>
                    <div class="small text-muted">{{ $order['customer']['phone'] ?? '' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted text-uppercase fw-bold mb-1">Delivery Address:</div>
                    <div class="fw-bold text-dark">{{ $order['customer']['address'] ?? 'Address Details' }}</div>
                    <div class="small text-muted">{{ $order['customer']['city'] ?? 'City' }}, Bangladesh</div>
                </div>
            </div>

            <!-- Itemized Table -->
            @if(!empty($order['items']))
                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item Description</th>
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
            @endif

            <!-- Total Calculation -->
            <div class="d-flex flex-column align-items-end border-top pt-3">
                <div class="d-flex justify-content-between w-100" style="max-width: 300px;">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-bold text-dark">৳{{ number_format($order['total'], 2) }}</span>
                </div>
                <div class="d-flex justify-content-between w-100 mt-1" style="max-width: 300px;">
                    <span class="text-muted">Delivery Charge:</span>
                    <span class="text-success fw-bold">FREE</span>
                </div>
                <div class="d-flex justify-content-between w-100 border-top mt-2 pt-2 fs-4 fw-bold" style="max-width: 300px;">
                    <span>Grand Total:</span>
                    <span class="text-danger">৳{{ number_format($order['total'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action Controls -->
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                <i class="fas fa-print me-2"></i> Print Invoice
            </button>
            <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-4 fw-bold">
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
