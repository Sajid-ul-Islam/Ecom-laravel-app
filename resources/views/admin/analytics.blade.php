@extends('layouts.admin')

@section('content')
<div class="py-2">
 <div class="container-fluid px-0">


 <!-- Top Header & Date Range Filter -->
 <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
 <div>
 <span class="badge bg-primary text-white mb-2 px-3 py-2 rounded-pill fw-bold"><i class="fas fa-chart-pie me-1"></i> Executive BI Dashboard</span>
 <h2 class="fw-bold text-dark mb-1">Business Intelligence & Analytics</h2>
 <p class="text-muted small mb-0">Real-time performance metrics and sales intelligence for Deen Commerce</p>
 </div>

 <div class="d-flex flex-wrap align-items-center gap-2">
 <!-- Date Filter -->
 <form method="GET" action="{{ route('admin.analytics') }}" class="d-flex align-items-center gap-2">
 <select name="range" class="form-select form-select-sm rounded-pill border-secondary fw-semibold" onchange="this.form.submit()">
 <option value="today" {{ $range === 'today' ? 'selected' : '' }}>Today</option>
 <option value="7days" {{ $range === '7days' ? 'selected' : '' }}>Last 7 Days</option>
 <option value="30days" {{ $range === '30days' ? 'selected' : '' }}>Last 30 Days</option>
 <option value="ytd" {{ $range === 'ytd' ? 'selected' : '' }}>Year to Date</option>
 </select>
 </form>

 <button onclick="window.print()" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
 <i class="fas fa-print me-1"></i> Export Report
 </button>
 <a href="{{ route('admin.analytics') }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
 <i class="fas fa-sync-alt me-1"></i> Live Refresh
 </a>
 </div>
 </div>

 <!-- 4 Key Executive Stat Cards -->
 <div class="row g-3 mb-4">
 <!-- Gross Revenue -->
 <div class="col-12 col-sm-6 col-xl-3">
 <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small text-muted fw-bold text-uppercase">Gross Revenue</span>
 <div class="bg-primary-subtle text-primary rounded-circle p-2"><i class="fas fa-bangladeshi-taka-sign fs-5"></i></div>
 </div>
 <div class="fs-2 fw-bold text-dark">৳{{ number_format($metrics['grossRevenue'], 2) }}</div>
 <div class="small text-success fw-bold mt-2">
 <i class="fas fa-arrow-up me-1"></i> +18.4% <span class="text-muted fw-normal">vs previous period</span>
 </div>
 </div>
 </div>

 <!-- Total Orders & AOV -->
 <div class="col-12 col-sm-6 col-xl-3">
 <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small text-muted fw-bold text-uppercase">Total Orders / AOV</span>
 <div class="bg-success-subtle text-success rounded-circle p-2"><i class="fas fa-shopping-cart fs-5"></i></div>
 </div>
 <div class="fs-2 fw-bold text-dark">{{ number_format($metrics['totalOrders']) }}</div>
 <div class="small text-muted mt-2">
 Avg Order Value: <strong class="text-dark">৳{{ number_format($metrics['aov'], 2) }}</strong>
 </div>
 </div>
 </div>

 <!-- Synced Catalog Health -->
 <div class="col-12 col-sm-6 col-xl-3">
 <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small text-muted fw-bold text-uppercase">Synced Fashion Catalog</span>
 <div class="bg-warning-subtle text-warning rounded-circle p-2"><i class="fas fa-tshirt fs-5"></i></div>
 </div>
 <div class="fs-2 fw-bold text-dark">{{ number_format($metrics['totalProducts']) }} <span class="fs-6 text-muted font-monospace">Items</span></div>
 <div class="small text-danger fw-bold mt-2">
 <i class="fas fa-exclamation-triangle me-1"></i> {{ $metrics['outOfStockCount'] }} Low Stock Alert
 </div>
 </div>
 </div>

 <!-- Conversion Rate -->
 <div class="col-12 col-sm-6 col-xl-3">
 <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <span class="small text-muted fw-bold text-uppercase">Store Conversion Rate</span>
 <div class="bg-danger-subtle text-danger rounded-circle p-2"><i class="fas fa-chart-line fs-5"></i></div>
 </div>
 <div class="fs-2 fw-bold text-dark">{{ $metrics['conversionRate'] }}%</div>
 <div class="small text-success fw-bold mt-2">
 <i class="fas fa-arrow-up me-1"></i> +0.6% <span class="text-muted fw-normal">Checkout completion</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Main BI Visualizations Row 1 -->
 <div class="row g-4 mb-4">
 <!-- Revenue & Sales Trend Line Chart -->
 <div class="col-12 col-lg-8">
 <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
 <div class="d-flex align-items-center justify-content-between mb-3">
 <div>
 <h5 class="fw-bold text-dark mb-0">Revenue & Sales Growth Trend</h5>
 <span class="small text-muted">Monthly sales revenue trajectory in BDT (৳)</span>
 </div>
 <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-monospace">2026 vs 2025</span>
 </div>
 <div style="height: 320px; position: relative;">
 <canvas id="revenueTrendChart"></canvas>
 </div>
 </div>
 </div>

 <!-- Sales Category Share Doughnut Chart -->
 <div class="col-12 col-lg-4">
 <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
 <div class="d-flex align-items-center justify-content-between mb-3">
 <div>
 <h5 class="fw-bold text-dark mb-0">Revenue by Category</h5>
 <span class="small text-muted">Sales share distribution</span>
 </div>
 </div>
 <div style="height: 280px; position: relative;" class="d-flex align-items-center justify-content-center">
 <canvas id="categoryShareChart"></canvas>
 </div>
 </div>
 </div>
 </div>

 <!-- Secondary BI Row 2: Top Products & Payment Gateways -->
 <div class="row g-4 mb-4">
 <!-- Top Performing Fashion Items Table -->
 <div class="col-12 col-lg-7">
 <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
 <div class="d-flex align-items-center justify-content-between mb-3">
 <div>
 <h5 class="fw-bold text-dark mb-0"><i class="fas fa-trophy text-warning me-2"></i> Top Performing Fashion Items</h5>
 <span class="small text-muted">Ranked by sales volume & revenue</span>
 </div>
 </div>

 <div class="table-responsive">
 <table class="table align-middle">
 <thead class="table-light">
 <tr>
 <th>Item Details</th>
 <th class="text-center">Units Sold</th>
 <th class="text-end">Total Revenue</th>
 </tr>
 </thead>
 <tbody>
 @foreach($metrics['topProducts'] as $item)
 <tr>
 <td>
 <div class="fw-bold text-dark">{{ $item['name'] }}</div>
 <div class="small text-muted font-monospace">SKU: {{ $item['sku'] }}</div>
 </td>
 <td class="text-center">
 <span class="badge bg-dark rounded-pill px-3">{{ $item['sales'] }}</span>
 </td>
 <td class="text-end fw-bold text-dark">
 ৳{{ number_format($item['revenue'], 2) }}
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
 </div>

 <!-- Payment Gateway Distribution & Low Stock Alerts -->
 <div class="col-12 col-lg-5">
 <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
 <h5 class="fw-bold text-dark mb-3"><i class="fas fa-wallet text-primary me-2"></i> Payment Method Share</h5>
 <div style="height: 180px; position: relative;">
 <canvas id="paymentChart"></canvas>
 </div>
 </div>

 <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
 <div class="d-flex align-items-center justify-content-between mb-3">
 <h5 class="fw-bold text-dark mb-0"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Restock Urgency Alerts</h5>
 <span class="badge bg-danger rounded-pill">{{ count($metrics['lowStockAlerts']) }} Items</span>
 </div>

 <div class="d-flex flex-column gap-2">
 @foreach($metrics['lowStockAlerts'] as $alert)
 <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
 <div>
 <div class="fw-bold small text-dark">{{ $alert['name'] }}</div>
 <div class="small text-muted font-monospace">{{ $alert['sku'] }}</div>
 </div>
 <div class="text-end">
 <span class="badge {{ $alert['urgency'] === 'CRITICAL' ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
 Qty Left: {{ $alert['qty'] }}
 </span>
 </div>
 </div>
 @endforeach
 </div>
 </div>
 </div>
 </div>

 </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
 // 1. Revenue & Sales Trend Line Chart
 const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
 new Chart(trendCtx, {
 type: 'line',
 data: {
 labels: @json($metrics['revenueTrend']['labels']),
 datasets: [
 {
 label: '2026 Revenue (৳)',
 data: @json($metrics['revenueTrend']['currentYear']),
 borderColor: '#2563eb',
 backgroundColor: 'rgba(37, 99, 235, 0.1)',
 fill: true,
 tension: 0.35,
 borderWidth: 3,
 },
 {
 label: '2025 Revenue (৳)',
 data: @json($metrics['revenueTrend']['previousYear']),
 borderColor: '#94a3b8',
 borderDash: [5, 5],
 fill: false,
 tension: 0.35,
 borderWidth: 2,
 }
 ]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: {
 legend: { position: 'top' }
 },
 scales: {
 y: {
 beginAtZero: true,
 ticks: {
 callback: function(val) { return '৳' + val.toLocaleString(); }
 }
 }
 }
 }
 });

 // 2. Category Share Doughnut Chart
 const catCtx = document.getElementById('categoryShareChart').getContext('2d');
 new Chart(catCtx, {
 type: 'doughnut',
 data: {
 labels: @json($metrics['categoryShare']['labels']),
 datasets: [{
 data: @json($metrics['categoryShare']['data']),
 backgroundColor: ['#0f172a', '#2563eb', '#e11d48', '#f59e0b', '#10b981'],
 borderWidth: 0,
 }]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: {
 legend: { position: 'bottom' }
 }
 }
 });

 // 3. Payment Method Distribution Pie Chart
 const payCtx = document.getElementById('paymentChart').getContext('2d');
 new Chart(payCtx, {
 type: 'pie',
 data: {
 labels: @json($metrics['paymentBreakdown']['labels']),
 datasets: [{
 data: @json($metrics['paymentBreakdown']['data']),
 backgroundColor: ['#e11d48', '#f59e0b', '#10b981', '#2563eb'],
 borderWidth: 0,
 }]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: {
 legend: { position: 'right' }
 }
 }
 });
});
</script>
@endsection
