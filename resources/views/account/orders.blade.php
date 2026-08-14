@extends('layouts.app')

@section('content')
<div class="py-5">
 <div class="container py-2">
 <!-- Header -->
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
 <div>
 <span class="deen-vibrant-pill orange py-0.5 px-2.5 mb-1.5 d-inline-flex align-items-center gap-1">
 <span class="material-symbols-outlined">inventory_2</span>
 <span>CLIENT DISPATCH LOG</span>
 </span>
 <h1 class="h3 fw-bold text-dark mb-1 font-display">My Orders & Courier Shipments</h1>
 <p class="text-secondary small mb-0">Track live dispatch progress, Steadfast/Pathao tracking codes, and digital tax receipts</p>
 </div>
 <div class="d-flex align-items-center gap-2">
 <a href="{{ route('account.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">person</span>
 <span>Account Profile</span>
 </a>
 <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-4 btn-sm fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
 <span class="material-symbols-outlined fs-5">storefront</span>
 <span>Shop Catalog</span>
 </a>
 </div>
 </div>

 <!-- Orders Table Card -->
 <div class="deen-frame p-4 p-md-5 bg-white shadow-sm">
 <div class="table-responsive">
 <table class="table align-middle">
 <thead class="table-light text-uppercase small text-secondary">
 <tr>
 <th>Order #</th>
 <th>Date Placed</th>
 <th>Status</th>
 <th>Payment</th>
 <th>Courier Partner</th>
 <th class="text-end">Total Amount</th>
 <th class="text-end">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($orders as $ord)
 @php
 $id = is_array($ord) ? $ord['id'] : $ord->id;
 $orderNum = is_array($ord) ? $ord['order_number'] : ($ord->order_number ?? $ord->id);
 $date = is_array($ord) ? $ord['created_at'] : $ord->created_at->format('M d, Y H:i');
 $status = is_array($ord) ? $ord['status'] : $ord->status;
 $total = is_array($ord) ? $ord['total_amount'] : $ord->total_amount;
 $payment = is_array($ord) ? ($ord['payment_method'] ?? 'bKash Mobile Banking') : ($ord->payment_method ?? 'bKash');
 $courier = is_array($ord) ? ($ord['courier'] ?? 'Steadfast Courier') : 'Steadfast Courier';
 $tracking = is_array($ord) ? ($ord['tracking_code'] ?? 'STF-882910') : 'STF-882910';
 @endphp
 <tr>
 <td>
 <div class="fw-bold text-dark font-display">#{{ $orderNum }}</div>
 <div class="small text-secondary font-monospace">Ref: WOO-{{ $id }}</div>
 </td>
 <td class="small text-secondary">{{ $date }}</td>
 <td>
 @if($status === 'completed')
 <span class="deen-vibrant-pill emerald py-0.5 px-2">Delivered</span>
 @elseif($status === 'in_transit' || $status === 'processing')
 <span class="deen-vibrant-pill orange py-0.5 px-2">In Transit</span>
 @else
 <span class="deen-vibrant-pill amber py-0.5 px-2">{{ ucfirst($status) }}</span>
 @endif
 </td>
 <td class="small text-dark">{{ $payment }}</td>
 <td>
 <div class="fw-semibold small text-dark">{{ $courier }}</div>
 <div class="small text-secondary font-monospace">{{ $tracking }}</div>
 </td>
 <td class="text-end fw-bold text-dark fs-6 font-display">৳{{ number_format($total, 2) }}</td>
 <td class="text-end">
 <div class="d-flex justify-content-end gap-1.5">
 <a href="{{ route('account.orders.track', $id) }}" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-none">
 <span class="material-symbols-outlined fs-6">local_shipping</span>
 <span>Track</span>
 </a>
 <a href="{{ route('store.order.success', $id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fw-semibold shadow-none" title="View Receipt">
 <span class="material-symbols-outlined fs-6">receipt</span>
 </a>
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="text-center py-5">
 <span class="material-symbols-outlined text-muted fs-1 mb-2">package_2</span>
 <h5 class="fw-bold text-dark">No Orders Found</h5>
 <p class="text-secondary small mb-3">You haven't placed any orders yet. Discover our premium denim collections.</p>
 <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold">Start Shopping</a>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
 </div>
</div>
@endsection
