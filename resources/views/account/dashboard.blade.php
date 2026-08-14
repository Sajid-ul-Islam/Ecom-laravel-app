@extends('layouts.app')

@section('content')
<div class="py-5">
 <div class="container py-2">

 <!-- ==========================================================================
 CUSTOMER PROFILE HEADER HERO BANNER
 ========================================================================== -->
 <div class="deen-frame p-4 p-md-5 mb-4 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
 <!-- Ambient Backdrop Glow -->
 <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(250, 84, 28, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 position-relative">
 <!-- Left Avatar & Profile Details -->
 <div class="d-flex align-items-center gap-3.5">
 <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-lg" style="width: 72px; height: 72px; background: linear-gradient(135deg, #fa541c 0%, #ea580c 100%); color: #ffffff; font-size: 1.8rem; font-family: var(--deen-font-display);">
 {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
 </div>
 <div>
 <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
 <span class="deen-vibrant-pill orange py-0.5 px-2.5">VIP Gold Member</span>
 <span class="deen-vibrant-pill amber py-0.5 px-2.5">{{ $loyaltyCoins }} Coins</span>
 </div>
 <h1 class="h3 fw-bold text-white mb-1 font-display">{{ $user->name ?? 'Tanvir Ahmed' }}</h1>
 <p class="text-white-50 small mb-0 d-flex align-items-center gap-2 flex-wrap">
 <span><i class="fas fa-envelope me-1 opacity-75"></i> {{ $user->email ?? 'customer@example.com' }}</span>
 <span>&bull;</span>
 <span><i class="fas fa-phone me-1 opacity-75"></i> {{ $user->phone ?? session('customer_profile.phone', '+880 1711-000000') }}</span>
 <span>&bull;</span>
 <span><i class="fas fa-location-dot me-1 opacity-75"></i> {{ $user->city ?? session('customer_profile.city', 'Dhaka, Bangladesh') }}</span>
 </p>
 </div>
 </div>

 <!-- Right Quick Navigation Actions -->
 <div class="d-flex align-items-center gap-2 flex-wrap">
 <a href="{{ route('store.index') }}" class="btn btn-outline-light rounded-pill px-3 py-2 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">storefront</span>
 <span>Shop Catalog</span>
 </a>
 <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger rounded-pill px-3 py-2 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">logout</span>
 <span>Sign Out</span>
 </a>
 </div>
 </div>
 </div>

 <!-- System Alerts & Flash Feedback -->
 @if(session('success'))
 <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2 p-3" style="background-color: #ecfdf5; color: #065f46;">
 <span class="material-symbols-outlined fs-5 text-success">check_circle</span>
 <span class="fw-semibold small">{{ session('success') }}</span>
 </div>
 @endif

 @if($errors->any())
 <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 p-3" style="background-color: #fef2f2; color: #991b1b;">
 <div class="fw-bold small mb-1 d-flex align-items-center gap-1.5">
 <span class="material-symbols-outlined fs-5">error</span>
 <span>Please correct the errors below:</span>
 </div>
 <ul class="small mb-0 ps-3">
 @foreach($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <!-- ==========================================================================
 METRIC STATS OVERVIEW CARDS (COLOR THEORY HARMONIZED)
 ========================================================================== -->
 <div class="row g-3 mb-4">
 <!-- Active Shipments -->
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-azure p-3.5 h-100 d-flex flex-column justify-content-between">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small fw-bold text-uppercase">In Transit</span>
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-15 text-primary">
 <span class="material-symbols-outlined fs-5">local_shipping</span>
 </div>
 </div>
 <div>
 <div class="h3 fw-bold text-dark mb-0 font-display">{{ $inTransit }}</div>
 <span class="text-secondary">Active Courier Parcels</span>
 </div>
 </div>
 </div>

 <!-- Total Orders Placed -->
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-linen p-3.5 h-100 d-flex flex-column justify-content-between">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small fw-bold text-uppercase">Total Orders</span>
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark bg-opacity-10 text-dark">
 <span class="material-symbols-outlined fs-5">inventory_2</span>
 </div>
 </div>
 <div>
 <div class="h3 fw-bold text-dark mb-0 font-display">{{ $totalOrders }}</div>
 <span class="text-secondary">Lifetime Wardrobe Pieces</span>
 </div>
 </div>
 </div>

 <!-- Lifetime Spent -->
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-sage p-3.5 h-100 d-flex flex-column justify-content-between">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small fw-bold text-uppercase">Total Spent</span>
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-15 text-success">
 <span class="material-symbols-outlined fs-5">payments</span>
 </div>
 </div>
 <div>
 <div class="h3 fw-bold text-dark mb-0 font-display">৳{{ number_format($totalSpent, 2) }}</div>
 <span class="text-secondary">Authentic Denim Invested</span>
 </div>
 </div>
 </div>

 <!-- VIP Reward Coins -->
 <div class="col-6 col-md-3">
 <div class="deen-frame deen-pastel-sand p-3.5 h-100 d-flex flex-column justify-content-between">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small fw-bold text-uppercase">VIP Coins</span>
 <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-20 text-warning">
 <span class="material-symbols-outlined fs-5">military_tech</span>
 </div>
 </div>
 <div>
 <div class="h3 fw-bold text-dark mb-0 font-display">{{ $loyaltyCoins }}</div>
 <span class="text-secondary">Redeem for ৳100-৳250 Off</span>
 </div>
 </div>
 </div>
 </div>

 <!-- ==========================================================================
 CUSTOMER PROFILE NAVIGATION TABS
 ========================================================================== -->
 <div class="deen-frame p-2 mb-4 bg-white shadow-2xs">
 <ul class="nav nav-pills nav-fill gap-2" id="accountProfileTabs" role="tablist">
 <li class="nav-item" role="presentation">
 <button class="nav-link active rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-1.5" id="track-tab-btn" data-bs-toggle="pill" data-bs-target="#tab-track" type="button" role="tab">
 <span class="material-symbols-outlined fs-5">local_shipping</span>
 <span>Track Live Order</span>
 </button>
 </li>
 <li class="nav-item" role="presentation">
 <button class="nav-link rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-1.5" id="orders-tab-btn" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab">
 <span class="material-symbols-outlined fs-5">inventory_2</span>
 <span>Order History</span>
 </button>
 </li>
 <li class="nav-item" role="presentation">
 <button class="nav-link rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-1.5" id="profile-tab-btn" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
 <span class="material-symbols-outlined fs-5">person</span>
 <span>Customer Profile & Address</span>
 </button>
 </li>
 <li class="nav-item" role="presentation">
 <button class="nav-link rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-1.5" id="rewards-tab-btn" data-bs-toggle="pill" data-bs-target="#tab-rewards" type="button" role="tab">
 <span class="material-symbols-outlined fs-5">military_tech</span>
 <span>VIP Rewards & Coupons</span>
 </button>
 </li>
 <li class="nav-item" role="presentation">
 <button class="nav-link rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-1.5" id="security-tab-btn" data-bs-toggle="pill" data-bs-target="#tab-security" type="button" role="tab">
 <span class="material-symbols-outlined fs-5">lock</span>
 <span>Security</span>
 </button>
 </li>
 </ul>
 </div>

 <!-- ==========================================================================
 TAB CONTENT PANELS
 ========================================================================== -->
 <div class="tab-content" id="accountProfileTabContent">

 <!-- ======================================================================
 TAB 1: LIVE ORDER TRACKING & TIMELINE
 ====================================================================== -->
 <div class="tab-pane fade show active" id="tab-track" role="tabpanel">
 @if($activeOrder)
 <div class="deen-frame p-4 p-md-5 bg-white mb-4 shadow-sm">
 <!-- Top Order Tracking Header -->
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 mb-4 border-bottom">
 <div>
 <div class="d-flex align-items-center gap-2 mb-1">
 <span class="deen-vibrant-pill emerald py-0.5 px-2.5 d-inline-flex align-items-center gap-1">
 <span class="material-symbols-outlined">local_shipping</span>
 <span>IN TRANSIT WITH COURIER</span>
 </span>
 <span class="text-secondary small">&bull; Placed on {{ $activeOrder['created_at'] }}</span>
 </div>
 <h2 class="h4 fw-bold text-dark mb-0 font-display">Order #{{ $activeOrder['order_number'] ?? $activeOrder['id'] }}</h2>
 </div>
 <div class="text-md-end">
 <div class="small text-secondary">Estimated Doorstep Delivery</div>
 <div class="fw-bold text-dark fs-5">{{ $activeOrder['estimated_delivery'] ?? now()->addDay()->format('M d, Y') }}</div>
 </div>
 </div>

 <!-- 5-STAGE INTERACTIVE PROGRESS STEPPER -->
 <div class="position-relative my-5 px-2 px-md-4">
 <!-- Background Track Line -->
 <div class="position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 4px; background: #e2e8f0; z-index: 1;">
 <div class="h-100" style="width: 60%; background: linear-gradient(90deg, #10b981 0%, #2563eb 100%);"></div>
 </div>

 <div class="d-flex justify-content-between position-relative">
 <!-- Step 1: Placed -->
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
 <div class="text-secondary">On Courier Van</div>
 </div>

 <!-- Step 4: Out for Delivery -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light border text-muted">
 <span class="material-symbols-outlined fs-5">two_wheeler</span>
 </div>
 <div class="fw-semibold text-muted small mt-2">Out for Delivery</div>
 <div class="text-secondary">Today 2-5 PM</div>
 </div>

 <!-- Step 5: Delivered -->
 <div class="text-center">
 <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light border text-muted">
 <span class="material-symbols-outlined fs-5">home</span>
 </div>
 <div class="fw-semibold text-muted small mt-2">Delivered</div>
 <div class="text-secondary">Doorstep</div>
 </div>
 </div>
 </div>

 <!-- LOGISTICS PARTNER & DESTINATION CARDS -->
 <div class="row g-3 mb-4">
 <!-- Courier Partner Details -->
 <div class="col-md-6">
 <div class="p-3.5 rounded-3 border h-100">
 <div class="d-flex align-items-center gap-2 mb-2">
 <span class="material-symbols-outlined text-danger fs-5">local_shipping</span>
 <h4 class="h6 fw-bold text-dark mb-0">Courier & Logistics Dispatch</h4>
 </div>
 <div class="fw-bold text-dark fs-6">{{ $activeOrder['courier'] ?? 'Steadfast Courier Express' }}</div>
 <div class="small text-secondary mt-1">
 Tracking Number: <strong class="text-dark font-monospace">{{ $activeOrder['tracking_code'] ?? 'STF-882910' }}</strong>
 </div>
 <div class="small text-secondary">
 Dispatch Hub: <span class="text-primary fw-semibold">Dhaka Central Logistics Hub - Tejgaon</span>
 </div>
 <div class="mt-2.5">
 <a href="https://steadfast.com.bd/t/{{ $activeOrder['tracking_code'] ?? 'STF-882910' }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-none">
 <span>Track on Courier Portal</span>
 <span class="material-symbols-outlined fs-6">open_in_new</span>
 </a>
 </div>
 </div>
 </div>

 <!-- Delivery Destination -->
 <div class="col-md-6">
 <div class="p-3.5 rounded-3 border h-100">
 <div class="d-flex align-items-center gap-2 mb-2">
 <span class="material-symbols-outlined text-primary fs-5">location_on</span>
 <h4 class="h6 fw-bold text-dark mb-0">Delivery Address & Contact</h4>
 </div>
 <div class="fw-bold text-dark">{{ $user->name ?? 'Valued Customer' }}</div>
 <div class="small text-secondary"><i class="fas fa-phone me-1 text-muted"></i> {{ $user->phone ?? session('customer_profile.phone', '+880 1711-000000') }}</div>
 <div class="small text-secondary"><i class="fas fa-map-marker-alt me-1 text-muted"></i> {{ $user->address ?? session('customer_profile.address', 'House 42, Road 11, Block D, Banani') }}, {{ $user->city ?? session('customer_profile.city', 'Dhaka') }}</div>
 <div class="mt-2">
 <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill">
 Payment: {{ $activeOrder['payment_method'] ?? 'bKash Mobile Banking' }}
 </span>
 </div>
 </div>
 </div>
 </div>

 <!-- Active Order Itemized List -->
 <h4 class="h6 fw-bold text-dark mb-3">Order Pieces ({{ $activeOrder['items_count'] ?? 2 }} Items)</h4>
 <div class="d-flex flex-column gap-2 mb-4">
 @if(!empty($activeOrder['items']))
 @foreach($activeOrder['items'] as $item)
 <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-white">
 <div class="d-flex align-items-center gap-3">
 <img src="{{ asset('images/deen-logo-dark.png') }}" class="rounded-2 border p-1" style="width: 48px; height: 48px; object-fit: contain;" alt="Deen Product" onerror="this.src='https://deencommerce.com/wp-content/uploads/2025/04/cropped-Deen-Logo-scaled-1.png'">
 <div>
 <div class="fw-semibold text-dark small">{{ $item['name'] }}</div>
 <div class="text-secondary small">Qty: {{ $item['qty'] }} &bull; ৳{{ number_format($item['price'], 2) }} each</div>
 </div>
 </div>
 <div class="fw-bold text-dark text-end">
 ৳{{ number_format($item['price'] * $item['qty'], 2) }}
 </div>
 </div>
 @endforeach
 @else
 <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-white">
 <div class="d-flex align-items-center gap-3">
 <img src="{{ asset('images/deen-logo-dark.png') }}" class="rounded-2 border p-1" style="width: 48px; height: 48px; object-fit: contain;" alt="Deen Product" onerror="this.src='https://deencommerce.com/wp-content/uploads/2025/04/cropped-Deen-Logo-scaled-1.png'">
 <div>
 <div class="fw-semibold text-dark small">High-End Raw Washed Jeans - Slim Fit (Size 32)</div>
 <div class="text-secondary small">Qty: 2 &bull; ৳2,490.00 each</div>
 </div>
 </div>
 <div class="fw-bold text-dark text-end">
 ৳4,980.00
 </div>
 </div>
 @endif
 </div>

 <!-- Grand Total & Receipt Trigger -->
 <div class="d-flex align-items-center justify-content-between pt-3 border-top">
 <a href="{{ route('store.order.success', $activeOrder['id'] ?? 202567) }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">receipt_long</span>
 <span>View Receipt Invoice</span>
 </a>
 <div class="text-end">
 <span class="text-secondary small">Grand Total:</span>
 <span class="h4 fw-bold text-dark ms-1 font-display">৳{{ number_format($activeOrder['total_amount'] ?? 4980, 2) }}</span>
 </div>
 </div>
 </div>
 @else
 <div class="deen-frame p-5 text-center bg-white">
 <span class="material-symbols-outlined text-muted fs-1 mb-2">package_2</span>
 <h4 class="fw-bold text-dark">No Active Shipments in Transit</h4>
 <p class="text-secondary small mb-4">When you place an order, live tracking and courier timelines will appear right here.</p>
 <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold">Browse Fashion Catalog</a>
 </div>
 @endif
 </div>

 <!-- ======================================================================
 TAB 2: ORDER HISTORY & INVOICES
 ====================================================================== -->
 <div class="tab-pane fade" id="tab-orders" role="tabpanel">
 <div class="deen-frame p-4 p-md-5 bg-white mb-4 shadow-sm">
 <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
 <div>
 <h3 class="h5 fw-bold text-dark mb-0 font-display">Order History & Invoices</h3>
 <span class="text-secondary small">Review past receipts and dispatch statuses</span>
 </div>
 <span class="badge bg-dark rounded-pill px-3 py-1.5">{{ count($orders) }} Orders Placed</span>
 </div>

 <div class="table-responsive">
 <table class="table align-middle">
 <thead class="table-light text-uppercase small text-secondary" style="font-size: 0.70rem; letter-spacing: 0.05em;">
 <tr>
 <th>Order #</th>
 <th>Date Placed</th>
 <th>Status</th>
 <th>Courier Partner</th>
 <th>Payment</th>
 <th class="text-end">Amount</th>
 <th class="text-end">Actions</th>
 </tr>
 </thead>
 <tbody>
 @foreach($orders as $ord)
 @php
 $id = is_array($ord) ? $ord['id'] : $ord->id;
 $orderNum = is_array($ord) ? $ord['order_number'] : ($ord->order_number ?? $ord->id);
 $date = is_array($ord) ? $ord['created_at'] : $ord->created_at->format('M d, Y H:i');
 $status = is_array($ord) ? $ord['status'] : $ord->status;
 $total = is_array($ord) ? $ord['total_amount'] : $ord->total_amount;
 $courier = is_array($ord) ? ($ord['courier'] ?? 'Steadfast Courier') : 'Steadfast Courier';
 $payment = is_array($ord) ? ($ord['payment_method'] ?? 'bKash') : 'bKash';
 @endphp
 <tr>
 <td>
 <span class="fw-bold text-dark">#{{ $orderNum }}</span>
 </td>
 <td class="small text-secondary">{{ $date }}</td>
 <td>
 @if($status === 'completed')
 <span class="deen-vibrant-pill emerald py-0.5 px-2">Completed</span>
 @elseif($status === 'in_transit' || $status === 'processing')
 <span class="deen-vibrant-pill orange py-0.5 px-2">In Transit</span>
 @else
 <span class="deen-vibrant-pill amber py-0.5 px-2">{{ ucfirst($status) }}</span>
 @endif
 </td>
 <td class="small text-dark">{{ $courier }}</td>
 <td class="small text-secondary">{{ $payment }}</td>
 <td class="text-end fw-bold text-dark font-display">৳{{ number_format($total, 2) }}</td>
 <td class="text-end">
 <div class="d-flex align-items-center justify-content-end gap-1.5">
 <a href="{{ route('account.orders.track', $id) }}" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-none">
 <span class="material-symbols-outlined fs-6">local_shipping</span>
 <span>Track</span>
 </a>
 <a href="{{ route('store.order.success', $id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fw-semibold shadow-none" title="View Invoice">
 <span class="material-symbols-outlined fs-6">receipt</span>
 </a>
 </div>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
 </div>

 <!-- ======================================================================
 TAB 3: CUSTOMER PROFILE & DELIVERY ADDRESS
 ====================================================================== -->
 <div class="tab-pane fade" id="tab-profile" role="tabpanel">
 <div class="row g-4">
 <!-- Profile Details Form -->
 <div class="col-lg-7">
 <div class="deen-frame p-4 p-md-5 bg-white h-100 shadow-sm">
 <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
 <span class="material-symbols-outlined text-primary fs-4">manage_accounts</span>
 <div>
 <h3 class="h5 fw-bold text-dark mb-0 font-display">Customer Profile Details</h3>
 <span class="text-secondary small">Update your contact & default delivery address</span>
 </div>
 </div>

 <form method="POST" action="{{ route('account.profile.update') }}">
 @csrf
 <div class="row g-3">
 <div class="col-md-6">
 <label class="form-label fw-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
 <div class="input-group">
 <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
 <input type="text" name="name" class="form-control bg-light border-start-0" value="{{ old('name', $user->name ?? 'Tanvir Ahmed') }}" required>
 </div>
 </div>

 <div class="col-md-6">
 <label class="form-label fw-semibold text-dark small">Email Address</label>
 <div class="input-group">
 <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
 <input type="email" class="form-control bg-light border-start-0" value="{{ $user->email ?? 'customer@example.com' }}" disabled readonly>
 </div>
 <div class="text-muted"><i class="fas fa-circle-check text-success me-1"></i> Verified client email address</div>
 </div>

 <div class="col-md-6">
 <label class="form-label fw-semibold text-dark small">Contact Phone Number <span class="text-danger">*</span></label>
 <div class="input-group">
 <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-phone"></i></span>
 <input type="text" name="phone" class="form-control bg-light border-start-0" value="{{ old('phone', $user->phone ?? session('customer_profile.phone', '+880 1711-000000')) }}" placeholder="+880 1711-000000" required>
 </div>
 </div>

 <div class="col-md-6">
 <label class="form-label fw-semibold text-dark small">City / Division <span class="text-danger">*</span></label>
 <select name="city" class="form-select bg-light">
 @php $curCity = old('city', $user->city ?? session('customer_profile.city', 'Dhaka')); @endphp
 <option value="Dhaka" {{ $curCity === 'Dhaka' ? 'selected' : '' }}>Dhaka (Inside City)</option>
 <option value="Chittagong" {{ $curCity === 'Chittagong' ? 'selected' : '' }}>Chittagong</option>
 <option value="Sylhet" {{ $curCity === 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
 <option value="Rajshahi" {{ $curCity === 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
 <option value="Khulna" {{ $curCity === 'Khulna' ? 'selected' : '' }}>Khulna</option>
 <option value="Barisal" {{ $curCity === 'Barisal' ? 'selected' : '' }}>Barisal</option>
 <option value="Rangpur" {{ $curCity === 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
 <option value="Mymensingh" {{ $curCity === 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
 </select>
 </div>

 <div class="col-12">
 <label class="form-label fw-semibold text-dark small">Street & Delivery Address <span class="text-danger">*</span></label>
 <div class="input-group">
 <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-location-dot"></i></span>
 <input type="text" name="address" class="form-control bg-light border-start-0" value="{{ old('address', $user->address ?? session('customer_profile.address', 'House 42, Road 11, Block D, Banani')) }}" placeholder="House, Road, Area, Landmark" required>
 </div>
 </div>

 <div class="col-md-6">
 <label class="form-label fw-semibold text-dark small">Postal Code / Thana</label>
 <input type="text" name="postal_code" class="form-control bg-light" value="{{ old('postal_code', $user->postal_code ?? session('customer_profile.postal_code', '1213')) }}" placeholder="e.g. 1213 / Banani">
 </div>

 <div class="col-12 mt-4">
 <button type="submit" class="btn btn-dark rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
 <span class="material-symbols-outlined fs-5">save</span>
 <span>Save Profile & Address Details</span>
 </button>
 </div>
 </div>
 </form>
 </div>
 </div>

 <!-- Client Status & Shipping Summary Card -->
 <div class="col-lg-5">
 <div class="deen-frame p-4 p-md-5 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
 <div>
 <div class="d-flex align-items-center gap-2 mb-3">
 <span class="material-symbols-outlined text-warning fs-4">verified</span>
 <h4 class="h6 fw-bold text-dark mb-0">Deen Client Privilege Summary</h4>
 </div>
 <p class="text-secondary small mb-4">Your saved delivery details are pre-filled at checkout for lightning-fast 1-tap ordering.</p>

 <div class="d-flex flex-column gap-3 mb-4">
 <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
 <div>
 <div class="fw-bold text-dark small">Complimentary Shipping</div>
 <div class="text-secondary">Orders above ৳2,000 unlock 100% free nationwide express delivery.</div>
 </div>
 <span class="material-symbols-outlined text-success fs-4">local_shipping</span>
 </div>

 <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
 <div>
 <div class="fw-bold text-dark small">7-Day Fit Guarantee</div>
 <div class="text-secondary">Hassle-free size exchanges and doorstep returns across Bangladesh.</div>
 </div>
 <span class="material-symbols-outlined text-primary fs-4">published_with_changes</span>
 </div>
 </div>
 </div>

 <div class="pt-3 border-top">
 <div class="small text-secondary mb-1">Need help with your profile or delivery?</div>
 <a href="https://t.me/DEEN_Commerce_bot" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 shadow-none">
 <i class="fab fa-telegram text-info"></i>
 <span>Chat with Assistant (@DEEN_Commerce_bot)</span>
 </a>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- ======================================================================
 TAB 4: VIP PRIVILEGE REWARDS & COUPONS
 ====================================================================== -->
 <div class="tab-pane fade" id="tab-rewards" role="tabpanel">
 <div class="deen-frame p-4 p-md-5 bg-white mb-4 shadow-sm">
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
 <div>
 <span class="deen-vibrant-pill amber py-0.5 px-2.5 mb-1">Privilege Club</span>
 <h3 class="h5 fw-bold text-dark mb-0 font-display">VIP Rewards & Voucher Vault</h3>
 </div>
 <div class="text-md-end">
 <span class="text-secondary small">Available Deen Coins:</span>
 <span class="h4 fw-bold text-warning ms-1 font-display">{{ $loyaltyCoins }} COINS</span>
 </div>
 </div>

 <div class="row g-3">
 <!-- Voucher 1 -->
 <div class="col-md-4">
 <div class="deen-frame deen-pastel-sand p-4 h-100 d-flex flex-column justify-content-between">
 <div>
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="badge bg-dark rounded-pill px-2.5 py-1">100 COINS</span>
 <span class="material-symbols-outlined text-warning fs-5">redeem</span>
 </div>
 <h4 class="h6 fw-bold text-dark mb-1">৳100 OFF Wardrobe Voucher</h4>
 <p class="text-secondary">Valid on all denim, shirts, and casual apparel orders above ৳1,500.</p>
 </div>
 <div class="mt-3">
 <button type="button" onclick="navigator.clipboard.writeText('DEEN10'); alert('🎉 Copied Voucher Code: DEEN10 (৳100 OFF applied at checkout)');" class="btn btn-sm btn-dark w-100 rounded-pill fw-semibold">
 Copy Code: DEEN10
 </button>
 </div>
 </div>
 </div>

 <!-- Voucher 2 -->
 <div class="col-md-4">
 <div class="deen-frame deen-pastel-azure p-4 h-100 d-flex flex-column justify-content-between">
 <div>
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="badge bg-primary rounded-pill px-2.5 py-1">250 COINS</span>
 <span class="material-symbols-outlined text-primary fs-5">redeem</span>
 </div>
 <h4 class="h6 fw-bold text-dark mb-1">৳250 OFF Premium Denim</h4>
 <p class="text-secondary">Valid on all Raw Washed and Vintage Selvedge Jeans orders above ৳3,000.</p>
 </div>
 <div class="mt-3">
 <button type="button" onclick="navigator.clipboard.writeText('DEEN25'); alert('🎉 Copied Voucher Code: DEEN25 (৳250 OFF applied at checkout)');" class="btn btn-sm btn-primary w-100 rounded-pill fw-semibold">
 Copy Code: DEEN25
 </button>
 </div>
 </div>
 </div>

 <!-- Voucher 3 -->
 <div class="col-md-4">
 <div class="deen-frame deen-pastel-sage p-4 h-100 d-flex flex-column justify-content-between">
 <div>
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="badge bg-success rounded-pill px-2.5 py-1">50 COINS</span>
 <span class="material-symbols-outlined text-success fs-5">local_shipping</span>
 </div>
 <h4 class="h6 fw-bold text-dark mb-1">Free Express Delivery Pass</h4>
 <p class="text-secondary">Enjoy 100% free delivery nationwide on any order with zero minimum spend.</p>
 </div>
 <div class="mt-3">
 <button type="button" onclick="navigator.clipboard.writeText('FREEDEL'); alert('🎉 Copied Voucher Code: FREEDEL (Free Delivery applied)');" class="btn btn-sm btn-success w-100 rounded-pill fw-semibold">
 Copy Code: FREEDEL
 </button>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- ======================================================================
 TAB 5: SECURITY & PASSWORD UPDATE
 ====================================================================== -->
 <div class="tab-pane fade" id="tab-security" role="tabpanel">
 <div class="deen-frame p-4 p-md-5 bg-white mb-4 shadow-sm" style="max-width: 600px;">
 <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
 <span class="material-symbols-outlined text-danger fs-4">lock_reset</span>
 <div>
 <h3 class="h5 fw-bold text-dark mb-0 font-display">Security & Password</h3>
 <span class="text-secondary small">Ensure your account credentials remain secure</span>
 </div>
 </div>

 <form method="POST" action="{{ route('account.password.update') }}">
 @csrf
 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Current Password <span class="text-danger">*</span></label>
 <input type="password" name="current_password" class="form-control bg-light" required placeholder="••••••••">
 </div>

 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">New Password <span class="text-danger">*</span></label>
 <input type="password" name="password" class="form-control bg-light" required minlength="8" placeholder="At least 8 characters">
 </div>

 <div class="mb-4">
 <label class="form-label fw-semibold text-dark small">Confirm New Password <span class="text-danger">*</span></label>
 <input type="password" name="password_confirmation" class="form-control bg-light" required minlength="8" placeholder="Repeat new password">
 </div>

 <button type="submit" class="btn btn-dark rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
 <span class="material-symbols-outlined fs-5">key</span>
 <span>Update Password</span>
 </button>
 </form>
 </div>
 </div>

 </div>

 </div>
</div>
@endsection
