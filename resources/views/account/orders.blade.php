@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-5">
            <div>
                <span class="badge bg-danger text-white mb-2 px-3 py-2 rounded-pill fw-bold">Order History</span>
                <h1 class="fw-bold text-dark display-5 mb-1">My Orders & Shipments</h1>
                <p class="text-muted mb-0">Track live delivery status and view order receipts</p>
            </div>
            <a href="{{ route('account.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Dashboard Overview
            </a>
        </div>

        <!-- Orders Table Card -->
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Date Placed</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Courier Partner</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-end">Action</th>
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
                                $payment = is_array($ord) ? ($ord['payment_method'] ?? 'Online Payment') : ($ord->payment_method ?? 'bKash');
                                $courier = is_array($ord) ? ($ord['courier'] ?? 'Steadfast Courier') : 'Steadfast Courier';
                                $tracking = is_array($ord) ? ($ord['tracking_code'] ?? 'STF-882910') : 'STF-882910';
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">#{{ $orderNum }}</div>
                                    <div class="small text-muted font-monospace">Ref: WOO-{{ $id }}</div>
                                </td>
                                <td class="small text-muted">{{ $date }}</td>
                                <td>
                                    @if($status === 'completed')
                                        <span class="badge bg-success rounded-pill px-3"><i class="fas fa-circle-check me-1"></i> Delivered</span>
                                    @elseif($status === 'processing')
                                        <span class="badge bg-warning text-dark rounded-pill px-3"><i class="fas fa-truck-fast me-1"></i> In Transit</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">{{ strtoupper($status) }}</span>
                                    @endif
                                </td>
                                <td class="small fw-semibold text-dark">{{ $payment }}</td>
                                <td>
                                    <div class="fw-bold small text-dark">{{ $courier }}</div>
                                    <div class="small text-muted font-monospace">{{ $tracking }}</div>
                                </td>
                                <td class="text-end fw-bold text-dark fs-5">৳{{ number_format($total, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('account.orders.track', $id) }}" class="btn btn-sm btn-danger rounded-pill fw-bold">
                                            Track Order <i class="fas fa-location-dot ms-1"></i>
                                        </a>
                                        <a href="{{ route('store.order.success', $id) }}" class="btn btn-sm btn-outline-dark rounded-pill fw-bold">
                                            Receipt
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted opacity-50 mb-3"></i>
                                    <h5>No Orders Found</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('store.index') }}" class="btn btn-primary rounded-pill px-4">Start Shopping</a>
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
