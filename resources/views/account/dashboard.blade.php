@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">

        <!-- Welcome Banner -->
        <div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-sm mb-4 position-relative overflow-hidden">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px;">
                    {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                </div>
                <div>
                    <span class="badge bg-danger mb-1 px-3 py-1 rounded-pill">Deen Customer Account</span>
                    <h2 class="fw-bold text-white mb-0">Welcome back, {{ $user->name ?? 'Valued Customer' }}!</h2>
                    <p class="text-white-50 small mb-0">{{ $user->email ?? 'customer@example.com' }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-muted fw-bold text-uppercase">Total Orders</span>
                        <i class="fas fa-shopping-bag text-primary fs-4"></i>
                    </div>
                    <div class="fs-2 fw-bold text-dark">{{ $totalOrders }}</div>
                    <a href="{{ route('account.orders') }}" class="small text-primary text-decoration-none fw-semibold">View Order History &rarr;</a>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-muted fw-bold text-uppercase">In Transit</span>
                        <i class="fas fa-truck-fast text-warning fs-4"></i>
                    </div>
                    <div class="fs-2 fw-bold text-dark">{{ $inTransit }}</div>
                    <span class="small text-muted">Active shipments being delivered</span>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-muted fw-bold text-uppercase">Total Spent</span>
                        <i class="fas fa-wallet text-success fs-4"></i>
                    </div>
                    <div class="fs-2 fw-bold text-dark">৳{{ number_format($totalSpent, 2) }}</div>
                    <span class="small text-muted">Lifetime purchases on Deen Store</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Info Form -->
            <div class="col-lg-5">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-user-gear text-primary me-2"></i> Shipping & Account Details</h5>
                    <form method="POST" action="{{ route('account.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name ?? 'Tanvir Ahmed') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="{{ session('customer_profile.phone') ?? '+880 1711-000000' }}" placeholder="+880 1711-000000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Shipping Address</label>
                            <input type="text" name="address" class="form-control rounded-3" value="{{ session('customer_profile.address') ?? 'House 42, Road 11, Banani' }}" placeholder="Street Address">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">City / District</label>
                            <input type="text" name="city" class="form-control rounded-3" value="{{ session('customer_profile.city') ?? 'Dhaka' }}" placeholder="Dhaka">
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold">Save Profile Details</button>
                    </form>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="col-lg-7">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-clock-rotate-left text-primary me-2"></i> Recent Orders</h5>
                        <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-dark rounded-pill fw-bold">All Orders</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                    @php
                                        $id = is_array($ord) ? $ord['id'] : $ord->id;
                                        $orderNum = is_array($ord) ? $ord['order_number'] : ($ord->order_number ?? $ord->id);
                                        $date = is_array($ord) ? $ord['created_at'] : $ord->created_at->format('M d, Y');
                                        $status = is_array($ord) ? $ord['status'] : $ord->status;
                                        $total = is_array($ord) ? $ord['total_amount'] : $ord->total_amount;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-dark">#{{ $orderNum }}</td>
                                        <td class="small text-muted">{{ $date }}</td>
                                        <td>
                                            <span class="badge {{ $status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill text-uppercase">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">৳{{ number_format($total, 2) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('account.orders.track', $id) }}" class="btn btn-sm btn-dark rounded-pill fw-bold">
                                                Track <i class="fas fa-location-dot ms-1 text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
