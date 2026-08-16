@extends('layouts.admin')

@section('content')
<div class="py-2">
 <div class="container-fluid px-0">

  <!-- Top Header & Date Range Filter -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
   <div>
    <span class="badge bg-primary text-white mb-2 px-3 py-2 rounded-pill fw-bold"><i class="fas fa-chart-pie me-1"></i> Executive BI Dashboard</span>
    <h2 class="fw-bold mb-1">Business Intelligence & Analytics</h2>
    <p class="text-muted small mb-0">
     Real-time performance metrics and sales intelligence for Deen Commerce — <span id="dataCoverage" class="fw-semibold text-info"></span>
    </p>
   </div>

   <div class="d-flex flex-wrap align-items-center gap-2">
    <!-- Date Filter -->
    <form method="GET" action="{{ route('admin.analytics') }}" class="d-flex align-items-center gap-2">
     <select name="range" class="form-select form-select-sm rounded-pill border-secondary fw-semibold" onchange="this.form.submit()">
      <option value="today" {{ $range === 'today' ? 'selected' : '' }}>Today</option>
      <option value="7days" {{ $range === '7days' ? 'selected' : '' }}>Last 7 Days</option>
      <option value="30days" {{ $range === '30days' ? 'selected' : '' }}>Last 30 Days</option>
      <option value="90days" {{ $range === '90days' ? 'selected' : '' }}>Last 90 Days</option>
      <option value="ytd" {{ $range === 'ytd' ? 'selected' : '' }}>Year to Date</option>
      <option value="all" {{ $range === 'all' ? 'selected' : '' }}>All Time</option>
     </select>
    </form>

    <a href="{{ route('admin.analytics.export', ['range' => $range]) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
     <i class="fas fa-file-csv me-1"></i> Export CSV
    </a>
    <a href="{{ route('admin.analytics', ['range' => $range]) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
     <i class="fas fa-sync-alt me-1"></i> Live Refresh
    </a>
   </div>
  </div>

  <!-- Executive KPI Cards -->
  <div class="row g-3 mb-4">
   <!-- Gross Revenue -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Gross Revenue</span>
      <div class="bg-primary-subtle text-primary rounded-circle p-2"><i class="fas fa-bangladeshi-taka-sign fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">৳{{ number_format($metrics['kpis']['revenue'], 2) }}</div>
     <div class="small mt-2">
      <span class="badge {{ $metrics['kpis']['revenueChange'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
       <i class="fas {{ $metrics['kpis']['revenueChange'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>{{ abs($metrics['kpis']['revenueChange']) }}%
      </span>
      <span class="text-muted fw-normal">vs previous period</span>
     </div>
    </div>
   </div>

   <!-- Net Revenue -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Net Revenue</span>
      <div class="bg-success-subtle text-success rounded-circle p-2"><i class="fas fa-wallet fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">৳{{ number_format($metrics['kpis']['netRevenue'], 2) }}</div>
     <div class="small text-muted mt-2">After shipping deductions</div>
    </div>
   </div>

   <!-- Total Orders & AOV -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Total Orders / AOV</span>
      <div class="bg-warning-subtle text-warning rounded-circle p-2"><i class="fas fa-shopping-cart fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">{{ number_format($metrics['kpis']['orders']) }}</div>
     <div class="small mt-2">
      <span class="badge {{ $metrics['kpis']['ordersChange'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
       <i class="fas {{ $metrics['kpis']['ordersChange'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>{{ abs($metrics['kpis']['ordersChange']) }}%
      </span>
      <span class="text-muted fw-normal">AOV: <strong class="text-dark">৳{{ number_format($metrics['kpis']['aov'], 2) }}</strong></span>
     </div>
    </div>
   </div>

   <!-- Units Sold -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Units Sold</span>
      <div class="bg-info-subtle text-info rounded-circle p-2"><i class="fas fa-boxes fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">{{ number_format($metrics['kpis']['units']) }}</div>
     <div class="small mt-2">
      <span class="badge {{ $metrics['kpis']['unitsChange'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
       <i class="fas {{ $metrics['kpis']['unitsChange'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>{{ abs($metrics['kpis']['unitsChange']) }}%
      </span>
      <span class="text-muted fw-normal">items this period</span>
     </div>
    </div>
   </div>

   <!-- Unique Customers -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Unique Customers</span>
      <div class="bg-danger-subtle text-danger rounded-circle p-2"><i class="fas fa-users fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">{{ number_format($metrics['kpis']['customers']) }}</div>
     <div class="small mt-2">
      <span class="badge {{ $metrics['kpis']['customersChange'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
       <i class="fas {{ $metrics['kpis']['customersChange'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>{{ abs($metrics['kpis']['customersChange']) }}%
      </span>
      <span class="text-muted fw-normal">distinct shoppers</span>
     </div>
    </div>
   </div>

   <!-- Repeat Rate -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Repeat Rate</span>
      <div class="bg-purple-subtle text-purple rounded-circle p-2"><i class="fas fa-repeat fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">{{ $metrics['kpis']['repeatRate'] }}%</div>
     <div class="small text-muted mt-2">Orders beyond first per customer</div>
    </div>
   </div>

   <!-- Discounts -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Discounts Given</span>
      <div class="bg-warning-subtle text-warning rounded-circle p-2"><i class="fas fa-tags fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">৳{{ number_format($metrics['kpis']['discounts'], 2) }}</div>
     <div class="small text-muted mt-2">Coupons & promotions</div>
    </div>
   </div>

   <!-- Shipping & Tax -->
   <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <span class="small text-muted fw-bold text-uppercase">Shipping / Tax</span>
      <div class="bg-secondary-subtle text-secondary rounded-circle p-2"><i class="fas fa-truck fs-5"></i></div>
     </div>
     <div class="fs-2 fw-bold text-dark">৳{{ number_format($metrics['kpis']['shipping'], 2) }}</div>
     <div class="small text-muted mt-2">Tax collected: <strong class="text-dark">৳{{ number_format($metrics['kpis']['tax'], 2) }}</strong></div>
    </div>
   </div>
  </div>

  <!-- Main BI Visualizations Row 1: Revenue Trend + Category Share -->
  <div class="row g-4 mb-4">
   <div class="col-12 col-lg-8">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0">Revenue & Sales Growth Trend</h5>
       <span class="small text-muted">BDT (৳) — {{ $metrics['label'] }} vs previous period</span>
      </div>
      <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-monospace" id="trendBadge">{{ $metrics['label'] }}</span>
     </div>
     <div style="height: 300px; position: relative;">
      <canvas id="revenueTrendChart"></canvas>
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-4">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0">Catalog by Category</h5>
       <span class="small text-muted">Product composition</span>
      </div>
     </div>
     <div style="height: 260px; position: relative;" class="d-flex align-items-center justify-content-center">
      <canvas id="categoryShareChart"></canvas>
     </div>
    </div>
   </div>
  </div>

  <!-- Row 2: Payment + Status + Geo -->
  <div class="row g-4 mb-4">
   <div class="col-12 col-lg-4">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <h5 class="fw-bold text-dark mb-3"><i class="fas fa-wallet text-primary me-2"></i> Payment Method Share</h5>
     <div style="height: 240px; position: relative;">
      <canvas id="paymentChart"></canvas>
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-4">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold text-dark mb-0"><i class="fas fa-clipboard-check text-success me-2"></i> Order Status</h5>
     </div>
     <div class="d-flex flex-column gap-3">
      @forelse($metrics['statusBreakdown']['labels'] as $i => $status)
      <div>
       <div class="d-flex justify-content-between mb-1">
        <span class="small fw-bold text-dark text-capitalize">{{ $status }}</span>
        <span class="small text-muted">{{ number_format($metrics['statusBreakdown']['orders'][$i]) }} orders · ৳{{ number_format($metrics['statusBreakdown']['revenue'][$i], 0) }}</span>
       </div>
       <div class="progress" style="height: 8px;">
        <div class="progress-bar {{ $status === 'completed' ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $metrics['kpis']['orders'] > 0 ? round(($metrics['statusBreakdown']['orders'][$i] / $metrics['kpis']['orders']) * 100, 1) : 0 }}%"></div>
       </div>
      </div>
      @empty
      <p class="text-muted small mb-0">No orders in this period.</p>
      @endforelse
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-4">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold text-dark mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i> Top Districts</h5>
      <span class="small text-muted">By orders</span>
     </div>
     <div style="height: 240px; position: relative;">
      <canvas id="geoChart"></canvas>
     </div>
    </div>
   </div>
  </div>

  <!-- Row 3: Hourly + Weekday -->
  <div class="row g-4 mb-4">
   <div class="col-12 col-lg-7">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <h5 class="fw-bold text-dark mb-3"><i class="fas fa-clock text-info me-2"></i> Orders by Hour of Day <span class="small text-muted fw-normal">(BDT timezone)</span></h5>
     <div style="height: 240px; position: relative;">
      <canvas id="hourlyChart"></canvas>
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-5">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <h5 class="fw-bold text-dark mb-3"><i class="fas fa-calendar-week text-warning me-2"></i> Orders by Weekday</h5>
     <div style="height: 240px; position: relative;">
      <canvas id="weekdayChart"></canvas>
     </div>
    </div>
   </div>
  </div>

  <!-- Row 4: Top Products + Top Customers -->
  <div class="row g-4 mb-4">
   <div class="col-12 col-lg-7">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0"><i class="fas fa-trophy text-warning me-2"></i> Top Performing Items</h5>
       <span class="small text-muted">Ranked by revenue in {{ $metrics['label'] }}</span>
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
        @forelse($metrics['topProducts'] as $item)
        <tr>
         <td>
          <div class="fw-bold text-dark">{{ $item['name'] }}</div>
          @if(!empty($item['sku']))
          <div class="small text-muted font-monospace">SKU: {{ $item['sku'] }}</div>
          @endif
         </td>
         <td class="text-center">
          <span class="badge bg-dark rounded-pill px-3">{{ number_format($item['units']) }}</span>
         </td>
         <td class="text-end fw-bold text-dark">৳{{ number_format($item['revenue'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-muted small text-center py-3">No sales in this period.</td></tr>
        @endforelse
       </tbody>
      </table>
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-5">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0"><i class="fas fa-crown text-primary me-2"></i> Top Customers</h5>
       <span class="small text-muted">Highest spenders in {{ $metrics['label'] }}</span>
      </div>
     </div>

     <div class="d-flex flex-column gap-3">
      @forelse($metrics['topCustomers'] as $customer)
      <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
       <div class="d-flex align-items-center gap-2">
        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
         <i class="fas fa-user fs-6"></i>
        </div>
        <div>
         <div class="fw-bold small text-dark">{{ $customer['email'] }}</div>
         <div class="small text-muted">{{ $customer['orders'] }} order{{ $customer['orders'] > 1 ? 's' : '' }}</div>
        </div>
       </div>
       <div class="text-end fw-bold text-dark small">৳{{ number_format($customer['revenue'], 2) }}</div>
      </div>
      @empty
      <p class="text-muted small mb-0">No customer data in this period.</p>
      @endforelse
     </div>
    </div>
   </div>
  </div>

  <!-- Row 5: Inventory Health + Sync Health -->
  <div class="row g-4 mb-4">
   <div class="col-12 col-lg-7">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0"><i class="fas fa-warehouse text-success me-2"></i> Inventory Health</h5>
       <span class="small text-muted">Current catalog snapshot</span>
      </div>
     </div>

     <div class="row g-3 mb-3">
      <div class="col-6 col-md-3">
       <div class="bg-light rounded-3 p-3 text-center">
        <div class="fs-4 fw-bold text-dark">{{ number_format($metrics['inventoryHealth']['total']) }}</div>
        <div class="small text-muted">Total SKUs</div>
       </div>
      </div>
      <div class="col-6 col-md-3">
       <div class="bg-success-subtle rounded-3 p-3 text-center">
        <div class="fs-4 fw-bold text-success">{{ number_format($metrics['inventoryHealth']['inStock']) }}</div>
        <div class="small text-muted">In Stock</div>
       </div>
      </div>
      <div class="col-6 col-md-3">
       <div class="bg-danger-subtle rounded-3 p-3 text-center">
        <div class="fs-4 fw-bold text-danger">{{ number_format($metrics['inventoryHealth']['outOfStock']) }}</div>
        <div class="small text-muted">Out of Stock</div>
       </div>
      </div>
      <div class="col-6 col-md-3">
       <div class="bg-warning-subtle rounded-3 p-3 text-center">
        <div class="fs-4 fw-bold text-warning">৳{{ number_format($metrics['inventoryHealth']['stockValue'], 0) }}</div>
        <div class="small text-muted">Stock Value</div>
       </div>
      </div>
     </div>

     <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="small fw-bold text-dark">Stock Availability</span>
      <span class="small text-muted">{{ $metrics['inventoryHealth']['inStockPct'] }}% in stock</span>
     </div>
     <div class="progress mb-4" style="height: 10px;">
      <div class="progress-bar bg-success" role="progressbar" style="width: {{ $metrics['inventoryHealth']['inStockPct'] }}%"></div>
      <div class="progress-bar bg-danger" role="progressbar" style="width: {{ 100 - $metrics['inventoryHealth']['inStockPct'] }}%"></div>
     </div>

     <h6 class="fw-bold text-dark mb-3"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Restock Urgency Alerts</h6>
     <div class="d-flex flex-column gap-2">
      @forelse($metrics['lowStockAlerts'] as $alert)
      <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
       <div>
        <div class="fw-bold small text-dark">{{ $alert['name'] }}</div>
        <div class="small text-muted font-monospace">{{ $alert['sku'] }}</div>
       </div>
       <div class="text-end">
        <span class="badge {{ $alert['urgency'] === 'CRITICAL' ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
         Qty Left: {{ number_format($alert['qty']) }}
        </span>
       </div>
      </div>
      @empty
      <p class="text-muted small mb-0">All items are sufficiently stocked.</p>
      @endforelse
     </div>
    </div>
   </div>

   <div class="col-12 col-lg-5">
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
     <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
       <h5 class="fw-bold text-dark mb-0"><i class="fas fa-heartbeat text-danger me-2"></i> Sync & API Health</h5>
       <span class="small text-muted">WooCommerce REST integration status</span>
      </div>
     </div>

     <div class="d-flex flex-column gap-3">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
       <span class="small fw-bold text-dark">API Requests</span>
       <span class="badge bg-dark rounded-pill px-3">{{ number_format($metrics['syncHealth']['requests']) }}</span>
      </div>
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
       <span class="small fw-bold text-dark">Success Rate</span>
       <span class="badge {{ $metrics['syncHealth']['successRate'] >= 90 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">{{ $metrics['syncHealth']['successRate'] }}%</span>
      </div>
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
       <span class="small fw-bold text-dark">Avg Latency</span>
       <span class="small text-muted">{{ $metrics['syncHealth']['avgLatencyMs'] }} ms</span>
      </div>
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
       <span class="small fw-bold text-dark">Failed Requests</span>
       <span class="badge {{ $metrics['syncHealth']['failures'] > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill px-3">{{ number_format($metrics['syncHealth']['failures']) }}</span>
      </div>
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
       <span class="small fw-bold text-dark">Unresolved Sync Failures</span>
       <span class="badge {{ $metrics['syncHealth']['unresolvedFailures'] > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill px-3">{{ number_format($metrics['syncHealth']['unresolvedFailures']) }}</span>
      </div>
      <div class="d-flex justify-content-between align-items-center">
       <span class="small fw-bold text-dark">Last Sync</span>
       <span class="small text-muted">{{ $metrics['syncHealth']['lastSyncAt'] ? \Illuminate\Support\Carbon::parse($metrics['syncHealth']['lastSyncAt'])->diffForHumans() : 'Never' }}</span>
      </div>
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
 const metrics = @json($metrics);

 // Data coverage footer note
 const ds = document.getElementById('dataCoverage');
 if (metrics.data_start) {
  ds.textContent = 'Synced orders span ' + metrics.data_start.slice(0, 10) + ' → ' + metrics.data_end.slice(0, 10);
 }

 const currencyTicks = (val) => '৳' + Number(val).toLocaleString();

 // 1. Revenue & Sales Trend Line Chart
 const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
 new Chart(trendCtx, {
  type: 'line',
  data: {
   labels: metrics.revenueTrend.labels,
   datasets: [
    {
     label: metrics.label + ' (৳)',
     data: metrics.revenueTrend.current,
     borderColor: '#2563eb',
     backgroundColor: 'rgba(37, 99, 235, 0.1)',
     fill: true,
     tension: 0.35,
     borderWidth: 3,
    },
    {
     label: 'Previous Period (৳)',
     data: metrics.revenueTrend.previous,
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
    legend: { position: 'top' },
    tooltip: {
     callbacks: { label: (ctx) => ctx.dataset.label + ': ৳' + Number(ctx.raw).toLocaleString() }
    }
   },
   scales: {
    y: {
     beginAtZero: true,
     ticks: { callback: currencyTicks }
    }
   }
  }
 });

 // 2. Category Share Doughnut Chart
 const catCtx = document.getElementById('categoryShareChart').getContext('2d');
 new Chart(catCtx, {
  type: 'doughnut',
  data: {
   labels: metrics.categoryShare.labels,
   datasets: [{
    data: metrics.categoryShare.data,
    backgroundColor: ['#0f172a', '#2563eb', '#e11d48', '#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#f97316'],
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
   labels: metrics.paymentBreakdown.labels,
   datasets: [{
    data: metrics.paymentBreakdown.revenue,
    backgroundColor: ['#e11d48', '#f59e0b', '#10b981', '#2563eb', '#8b5cf6', '#06b6d4'],
    borderWidth: 0,
   }]
  },
  options: {
   responsive: true,
   maintainAspectRatio: false,
   plugins: {
    legend: { position: 'right' },
    tooltip: {
     callbacks: { label: (ctx) => ' ' + ctx.label + ': ৳' + Number(ctx.raw).toLocaleString() }
    }
   }
  }
 });

 // 4. Geo Breakdown Bar Chart
 const geoCtx = document.getElementById('geoChart').getContext('2d');
 new Chart(geoCtx, {
  type: 'bar',
  data: {
   labels: metrics.geoBreakdown.labels,
   datasets: [{
    label: 'Orders',
    data: metrics.geoBreakdown.orders,
    backgroundColor: 'rgba(225, 29, 72, 0.75)',
    borderRadius: 6,
   }]
  },
  options: {
   indexAxis: 'y',
   responsive: true,
   maintainAspectRatio: false,
   plugins: { legend: { display: false } },
   scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
  }
 });

 // 5. Hourly Distribution Bar Chart
 const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
 new Chart(hourlyCtx, {
  type: 'bar',
  data: {
   labels: metrics.hourlyDistribution.labels,
   datasets: [{
    label: 'Orders',
    data: metrics.hourlyDistribution.orders,
    backgroundColor: 'rgba(6, 182, 212, 0.7)',
    borderRadius: 4,
   }]
  },
  options: {
   responsive: true,
   maintainAspectRatio: false,
   plugins: { legend: { display: false } },
   scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
  }
 });

 // 6. Weekday Distribution Bar Chart
 const weekdayCtx = document.getElementById('weekdayChart').getContext('2d');
 new Chart(weekdayCtx, {
  type: 'bar',
  data: {
   labels: metrics.weekdayDistribution.labels,
   datasets: [{
    label: 'Orders',
    data: metrics.weekdayDistribution.orders,
    backgroundColor: 'rgba(245, 158, 11, 0.75)',
    borderRadius: 6,
   }]
  },
  options: {
   responsive: true,
   maintainAspectRatio: false,
   plugins: { legend: { display: false } },
   scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
  }
 });
});
</script>
@endsection
