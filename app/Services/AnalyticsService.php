<?php

namespace App\Services;

use App\Models\WooApiLog;
use App\Models\WooOrder;
use App\Models\WooOrderItem;
use App\Models\WooProduct;
use App\Models\WooSyncFailure;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Business-intelligence aggregations over the synced WooCommerce dataset.
 *
 * Every method reads real rows from the local sync tables (woo_orders,
 * woo_order_items, woo_products, woo_api_logs, woo_sync_failures) and is
 * filtered by a friendly date-range key. Results are cached per range.
 */
class AnalyticsService
{
    /**
     * WooCommerce Bangladesh district codes (i18n/states.php) used by the
     * live store's checkout state selector — mapped here for the geo matrix.
     */
    protected const BD_STATES = [
        'BD-05' => 'Bagerhat', 'BD-01' => 'Bandarban', 'BD-02' => 'Barguna',
        'BD-06' => 'Barishal', 'BD-07' => 'Bhola', 'BD-03' => 'Bogura',
        'BD-04' => 'Brahmanbaria', 'BD-09' => 'Chandpur', 'BD-10' => 'Chattogram',
        'BD-12' => 'Chuadanga', 'BD-11' => "Cox's Bazar", 'BD-08' => 'Cumilla',
        'BD-13' => 'Dhaka', 'BD-14' => 'Dinajpur', 'BD-15' => 'Faridpur',
        'BD-16' => 'Feni', 'BD-19' => 'Gaibandha', 'BD-18' => 'Gazipur',
        'BD-17' => 'Gopalganj', 'BD-20' => 'Habiganj', 'BD-21' => 'Jamalpur',
        'BD-22' => 'Jashore', 'BD-25' => 'Jhalokati', 'BD-23' => 'Jhenaidah',
        'BD-24' => 'Joypurhat', 'BD-29' => 'Khagrachhari', 'BD-27' => 'Khulna',
        'BD-26' => 'Kishoreganj', 'BD-28' => 'Kurigram', 'BD-30' => 'Kushtia',
        'BD-31' => 'Lakshmipur', 'BD-32' => 'Lalmonirhat', 'BD-36' => 'Madaripur',
        'BD-37' => 'Magura', 'BD-33' => 'Manikganj', 'BD-39' => 'Meherpur',
        'BD-38' => 'Moulvibazar', 'BD-35' => 'Munshiganj', 'BD-34' => 'Mymensingh',
        'BD-48' => 'Naogaon', 'BD-43' => 'Narail', 'BD-40' => 'Narayanganj',
        'BD-42' => 'Narsingdi', 'BD-44' => 'Natore', 'BD-45' => 'Nawabganj',
        'BD-41' => 'Netrakona', 'BD-46' => 'Nilphamari', 'BD-47' => 'Noakhali',
        'BD-49' => 'Pabna', 'BD-52' => 'Panchagarh', 'BD-51' => 'Patuakhali',
        'BD-50' => 'Pirojpur', 'BD-53' => 'Rajbari', 'BD-54' => 'Rajshahi',
        'BD-56' => 'Rangamati', 'BD-55' => 'Rangpur', 'BD-58' => 'Satkhira',
        'BD-62' => 'Shariatpur', 'BD-57' => 'Sherpur', 'BD-59' => 'Sirajganj',
        'BD-61' => 'Sunamganj', 'BD-60' => 'Sylhet', 'BD-63' => 'Tangail',
        'BD-64' => 'Thakurgaon',
    ];

    /** Districts with no orders yet — avoids polluting the geo top-N. */
    protected const EMPTY_BD_STATES = [
        'BD-06', 'BD-07', 'BD-09', 'BD-12', 'BD-14', 'BD-15', 'BD-16', 'BD-17',
        'BD-19', 'BD-20', 'BD-22', 'BD-23', 'BD-24', 'BD-25', 'BD-26', 'BD-28',
        'BD-29', 'BD-30', 'BD-31', 'BD-32', 'BD-36', 'BD-37', 'BD-38', 'BD-39',
        'BD-41', 'BD-42', 'BD-43', 'BD-44', 'BD-45', 'BD-46', 'BD-48', 'BD-49',
        'BD-50', 'BD-51', 'BD-52', 'BD-53', 'BD-54', 'BD-56', 'BD-58', 'BD-59',
        'BD-61', 'BD-62', 'BD-64',
    ];

    /**
     * Compute the full BI metrics payload for a date range.
     *
     * @return array<string, mixed>
     */
    public function metrics(string $range = '30days'): array
    {
        $cacheKey = 'admin_bi_metrics_v2_' . $range;

        return Cache::remember($cacheKey, 300, function () use ($range) {
            try {
                return $this->compute($range);
            } catch (Throwable $e) {
                report($e);

                return $this->emptyPayload($range);
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function compute(string $range): array
    {
        [$start, $prevStart] = $this->window($range);

        $current = $this->orderScope($start);
        $previous = $this->orderScope($prevStart, $start);

        $kpis = $this->kpis($current, $previous);

        return [
            'range' => $range,
            'label' => $this->rangeLabel($range),
            'generated_at' => now()->toIso8601String(),
            'data_start' => WooOrder::query()->min('woo_created_at'),
            'data_end' => WooOrder::query()->max('woo_created_at'),
            'kpis' => $kpis,
            'revenueTrend' => $this->revenueTrend($range, $start, $prevStart),
            'categoryShare' => $this->categoryShare(),
            'paymentBreakdown' => $this->paymentBreakdown($current, $previous),
            'statusBreakdown' => $this->statusBreakdown($current),
            'geoBreakdown' => $this->geoBreakdown($start),
            'hourlyDistribution' => $this->hourlyDistribution($start),
            'weekdayDistribution' => $this->weekdayDistribution($start),
            'topProducts' => $this->topProducts($start),
            'topCustomers' => $this->topCustomers($start),
            'inventoryHealth' => $this->inventoryHealth(),
            'lowStockAlerts' => $this->lowStockAlerts(),
            'syncHealth' => $this->syncHealth($start),
        ];
    }

    /**
     * Window boundaries: [$start, $prevStart] where the previous window is
     * [$prevStart, $start). A null start means "all time" (no upper bound).
     *
     * @return array{CarbonImmutable|null, CarbonImmutable|null}
     */
    protected function window(string $range): array
    {
        $now = CarbonImmutable::now();

        return match ($range) {
            'today' => [$now->startOfDay(), $now->startOfDay()->subDay()],
            '7days' => [$now->subDays(7), $now->subDays(14)],
            '30days' => [$now->subDays(30), $now->subDays(60)],
            '90days' => [$now->subDays(90), $now->subDays(180)],
            'ytd' => [$now->startOfYear(), $now->startOfYear()->subYear()],
            default => [null, null],
        };
    }

    protected function rangeLabel(string $range): string
    {
        return match ($range) {
            'today' => 'Today',
            '7days' => 'Last 7 Days',
            '30days' => 'Last 30 Days',
            '90days' => 'Last 90 Days',
            'ytd' => 'Year to Date',
            default => 'All Time',
        };
    }

    protected function orderScope(?CarbonImmutable $start, ?CarbonImmutable $end = null)
    {
        return WooOrder::query()
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->when($end, fn ($q) => $q->where('woo_created_at', '<', $end));
    }

    /**
     * @return array<string, mixed>
     */
    protected function kpis($current, $previous): array
    {
        $revenue = (float) (clone $current)->sum('total');
        $prevRevenue = (float) (clone $previous)->sum('total');
        $orders = (clone $current)->count();
        $prevOrders = (clone $previous)->count();

        $orderIds = (clone $current)->select('id');
        $prevOrderIds = (clone $previous)->select('id');

        $units = WooOrderItem::whereIn('woo_order_id', $orderIds)->sum('quantity');
        $prevUnits = WooOrderItem::whereIn('woo_order_id', $prevOrderIds)->sum('quantity');

        $customers = (clone $current)->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')->distinct()->count('customer_email');
        $prevCustomers = (clone $previous)->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')->distinct()->count('customer_email');

        $repeatRate = $orders > 0 && $customers > 0
            ? round((($orders - $customers) / $customers) * 100, 1)
            : 0.0;
        $prevRepeatRate = $prevOrders > 0 && $prevCustomers > 0
            ? round((($prevOrders - $prevCustomers) / $prevCustomers) * 100, 1)
            : 0.0;

        return [
            'revenue' => $revenue,
            'revenueChange' => $this->pctChange($revenue, $prevRevenue),
            'netRevenue' => round($revenue - (float) (clone $current)->sum('shipping_total'), 2),
            'orders' => $orders,
            'ordersChange' => $this->pctChange($orders, $prevOrders),
            'aov' => $orders > 0 ? round($revenue / $orders, 2) : 0.0,
            'aovChange' => $this->pctChange(
                $orders > 0 ? $revenue / $orders : 0,
                $prevOrders > 0 ? $prevRevenue / $prevOrders : 0
            ),
            'units' => (int) $units,
            'unitsChange' => $this->pctChange($units, $prevUnits),
            'customers' => (int) $customers,
            'customersChange' => $this->pctChange($customers, $prevCustomers),
            'repeatRate' => $repeatRate,
            'repeatRateChange' => $this->pctChange($repeatRate, $prevRepeatRate),
            'discounts' => (float) (clone $current)->sum('discount_total'),
            'shipping' => (float) (clone $current)->sum('shipping_total'),
            'tax' => (float) (clone $current)->sum('total_tax'),
            'subtotal' => (float) (clone $current)->sum('subtotal'),
        ];
    }

    /**
     * Revenue series. Hourly for "today", daily for 7/30/90-day windows,
     * monthly for YTD / all-time. Includes the previous period overlay.
     *
     * @return array<string, mixed>
     */
    protected function revenueTrend(string $range, ?CarbonImmutable $start, ?CarbonImmutable $prevStart): array
    {
        $format = $range === 'today' ? 'H:00' : ($range === 'ytd' || $range === 'all' ? 'M Y' : 'd M');

        $rows = $this->trendRows($start, $range);
        $prevRows = $this->trendRows($prevStart, $range, $start);

        if ($range === 'today') {
            $labels = collect(range(0, 23))->map(fn ($h) => sprintf('%02d:00', $h))->all();
            $current = collect(range(0, 23))->map(fn ($h) => (float) ($rows["$h"] ?? 0))->all();
            $previous = collect(range(0, 23))->map(fn ($h) => (float) ($prevRows["$h"] ?? 0))->all();
        } elseif ($range === 'ytd' || $range === 'all') {
            $months = $rows->keys()->merge($prevRows->keys())->unique()->sort();
            $labels = $months->map(fn ($m) => CarbonImmutable::createFromFormat('Y-m', $m)->format($format))->values()->all();
            $current = $months->map(fn ($m) => (float) ($rows[$m] ?? 0))->values()->all();
            $previous = $months->map(fn ($m) => (float) ($prevRows[$m] ?? 0))->values()->all();
        } else {
            $labels = $rows->keys()->map(fn ($d) => CarbonImmutable::parse($d)->format($format))->values()->all();
            $current = $rows->values()->map(fn ($v) => (float) $v)->all();
            $previous = $rows->keys()->map(fn ($d) => (float) ($prevRows[$d] ?? 0))->values()->all();
        }

        return compact('labels', 'current', 'previous');
    }

    /**
     * Aggregate revenue per bucket key within [start, end).
     * Key is "H" (hour), "Y-m" (month) or "Y-m-d" (day).
     *
     * @return \Illuminate\Support\Collection<string, float>
     */
    protected function trendRows(?CarbonImmutable $start, string $range, ?CarbonImmutable $end = null)
    {
        $expr = match ($range) {
            'today' => "DATE_FORMAT(woo_created_at, '%H')",
            'ytd', 'all' => "DATE_FORMAT(woo_created_at, '%Y-%m')",
            default => "DATE_FORMAT(woo_created_at, '%Y-%m-%d')",
        };

        return DB::table('woo_orders')
            ->selectRaw("{$expr} AS bucket, SUM(total) AS revenue")
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->when($end, fn ($q) => $q->where('woo_created_at', '<', $end))
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->pluck('revenue', 'bucket')
            ->mapWithKeys(fn ($v, $k) => [$k => (float) $v]);
    }

    /**
     * Catalog composition: products per category (from raw product payloads).
     * All synced products carry categories, so this is complete data.
     *
     * @return array<string, mixed>
     */
    protected function categoryShare(): array
    {
        $counts = [];

        WooProduct::query()->pluck('raw_payload')->each(function ($payload) use (&$counts) {
            if (! is_array($payload)) {
                return;
            }

            foreach ($payload['categories'] ?? [] as $category) {
                $name = $category['name'] ?? null;
                if ($name) {
                    $counts[$name] = ($counts[$name] ?? 0) + 1;
                }
            }
        });

        arsort($counts);
        $counts = array_slice($counts, 0, 8, true);

        return [
            'labels' => array_keys($counts),
            'data' => array_values($counts),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function paymentBreakdown($current, $previous): array
    {
        $rows = (clone $current)->selectRaw('COALESCE(payment_method_title, "Other") AS label, COUNT(*) AS orders, SUM(total) AS revenue')
            ->groupBy('label')->orderByDesc('revenue')->get();

        $labels = $rows->pluck('label')->all();
        $orders = $rows->pluck('orders')->map(fn ($v) => (int) $v)->all();
        $revenue = $rows->pluck('revenue')->map(fn ($v) => (float) $v)->all();

        return compact('labels', 'orders', 'revenue');
    }

    /**
     * @return array<string, mixed>
     */
    protected function statusBreakdown($current): array
    {
        $rows = (clone $current)->selectRaw('status, COUNT(*) AS orders, SUM(total) AS revenue')
            ->groupBy('status')->orderByDesc('orders')->get();

        return [
            'labels' => $rows->pluck('status')->all(),
            'orders' => $rows->pluck('orders')->map(fn ($v) => (int) $v)->all(),
            'revenue' => $rows->pluck('revenue')->map(fn ($v) => (float) $v)->all(),
        ];
    }

    /**
     * Orders per Bangladesh district (billing->state), top 10.
     *
     * @return array<string, mixed>
     */
    protected function geoBreakdown(?CarbonImmutable $start): array
    {
        $rows = DB::table('woo_orders')
            ->selectRaw("billing->>'$.state' AS state, COUNT(*) AS orders, SUM(total) AS revenue")
            ->whereNotNull('billing')
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->groupBy('state')
            ->orderByDesc('orders')
            ->get()
            ->reject(fn ($row) => empty($row->state))
            ->take(10);

        $labels = $rows->map(function ($row) {
            $code = $row->state ?? '';

            return self::BD_STATES[$code] ?? ($code ?: 'Unknown');
        })->all();

        $orders = $rows->pluck('orders')->map(fn ($v) => (int) $v)->all();
        $revenue = $rows->pluck('revenue')->map(fn ($v) => (float) $v)->all();

        return compact('labels', 'orders', 'revenue');
    }

    /**
     * @return array<string, mixed>
     */
    protected function hourlyDistribution(?CarbonImmutable $start): array
    {
        $rows = DB::table('woo_orders')
            ->selectRaw("DATE_FORMAT(DATE_ADD(woo_created_at, INTERVAL 6 HOUR), '%H') AS hour, COUNT(*) AS orders")
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->groupBy('hour')->orderBy('hour')->get()
            ->pluck('orders', 'hour');

        return [
            'labels' => collect(range(0, 23))->map(fn ($h) => sprintf('%02d:00', $h))->all(),
            'orders' => collect(range(0, 23))->map(fn ($h) => (int) ($rows["$h"] ?? 0))->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function weekdayDistribution(?CarbonImmutable $start): array
    {
        $names = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $rows = DB::table('woo_orders')
            ->selectRaw("WEEKDAY(DATE_ADD(woo_created_at, INTERVAL 6 HOUR)) AS dow, COUNT(*) AS orders, SUM(total) AS revenue")
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->groupBy('dow')->orderBy('dow')->get();

        $labels = collect($names);
        $orders = $labels->map(fn ($name, $idx) => (int) ($rows->firstWhere('dow', $idx)->orders ?? 0))->values()->all();
        $revenue = $labels->map(fn ($name, $idx) => (float) ($rows->firstWhere('dow', $idx)->revenue ?? 0))->values()->all();

        return ['labels' => $names, 'orders' => $orders, 'revenue' => $revenue];
    }

    /**
     * Top selling items by revenue — aggregated across order line items.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function topProducts(?CarbonImmutable $start): array
    {
        return WooOrderItem::query()
            ->whereIn('woo_order_id', DB::table('woo_orders')->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))->select('id'))
            ->selectRaw('name, MAX(sku) AS sku, SUM(quantity) AS units, SUM(total) AS revenue')
            ->groupBy('name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'sku' => $row->sku,
                'units' => (int) $row->units,
                'revenue' => (float) $row->revenue,
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function topCustomers(?CarbonImmutable $start): array
    {
        return DB::table('woo_orders')
            ->when($start, fn ($q) => $q->where('woo_created_at', '>=', $start))
            ->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')
            ->selectRaw('customer_email, COUNT(*) AS orders, SUM(total) AS revenue')
            ->groupBy('customer_email')
            ->orderByDesc('revenue')
            ->limit(6)
            ->get()
            ->map(fn ($row) => [
                'email' => $row->customer_email,
                'orders' => (int) $row->orders,
                'revenue' => (float) $row->revenue,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function inventoryHealth(): array
    {
        $total = WooProduct::query()->count();
        $inStock = WooProduct::query()->where('stock_status', 'instock')->count();
        $outOfStock = WooProduct::query()->where('stock_status', 'outofstock')->count();

        $stockValue = (float) DB::table('woo_products')
            ->where('stock_quantity', '>', 0)
            ->selectRaw('SUM(price * stock_quantity) AS value')
            ->value('value') ?? 0.0;

        return [
            'total' => $total,
            'inStock' => $inStock,
            'outOfStock' => $outOfStock,
            'stockValue' => $stockValue,
            'inStockPct' => $total > 0 ? round(($inStock / $total) * 100, 1) : 0.0,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function lowStockAlerts(): array
    {
        return WooProduct::query()
            ->where(function ($q) {
                $q->where('stock_status', 'outofstock')
                    ->orWhere('stock_quantity', '<=', 5);
            })
            ->where('stock_quantity', '!=', null)
            ->orderBy('stock_quantity')
            ->limit(8)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->name,
                'sku' => $p->sku,
                'qty' => (int) $p->stock_quantity,
                'urgency' => ((int) $p->stock_quantity) <= 0 ? 'CRITICAL' : 'WARNING',
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function syncHealth(?CarbonImmutable $start): array
    {
        $logs = WooApiLog::query()
            ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
            ->selectRaw('COUNT(*) AS total, SUM(success = 1) AS ok, AVG(response_time_ms) AS avg_ms, SUM(success = 0) AS failed')
            ->first();

        $lastSyncOrder = WooOrder::query()->max('last_synced_at');
        $lastSyncProduct = WooProduct::query()->max('last_synced_at');
        $lastSync = $lastSyncOrder && $lastSyncProduct
            ? max((string) $lastSyncOrder, (string) $lastSyncProduct)
            : ($lastSyncOrder ?: $lastSyncProduct);

        $total = (int) ($logs->total ?? 0);

        return [
            'requests' => $total,
            'successRate' => $total > 0 ? round((($logs->ok ?? 0) / $total) * 100, 1) : 0.0,
            'avgLatencyMs' => $total > 0 ? round((float) ($logs->avg_ms ?? 0), 1) : 0.0,
            'failures' => (int) ($logs->failed ?? 0),
            'unresolvedFailures' => WooSyncFailure::query()->unresolved()->count(),
            'lastSyncAt' => $lastSync ? CarbonImmutable::parse($lastSync)->toIso8601String() : null,
        ];
    }

    protected function pctChange(float|int $current, float|int $previous): float
    {
        if ((float) $previous == 0.0) {
            return 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyPayload(string $range): array
    {
        return [
            'range' => $range,
            'label' => $this->rangeLabel($range),
            'generated_at' => now()->toIso8601String(),
            'data_start' => null,
            'data_end' => null,
            'kpis' => [
                'revenue' => 0, 'revenueChange' => 0, 'netRevenue' => 0,
                'orders' => 0, 'ordersChange' => 0, 'aov' => 0, 'aovChange' => 0,
                'units' => 0, 'unitsChange' => 0, 'customers' => 0, 'customersChange' => 0,
                'repeatRate' => 0, 'repeatRateChange' => 0,
                'discounts' => 0, 'shipping' => 0, 'tax' => 0, 'subtotal' => 0,
            ],
            'revenueTrend' => ['labels' => [], 'current' => [], 'previous' => []],
            'categoryShare' => ['labels' => [], 'data' => []],
            'paymentBreakdown' => ['labels' => [], 'orders' => [], 'revenue' => []],
            'statusBreakdown' => ['labels' => [], 'orders' => [], 'revenue' => []],
            'geoBreakdown' => ['labels' => [], 'orders' => [], 'revenue' => []],
            'hourlyDistribution' => ['labels' => [], 'orders' => []],
            'weekdayDistribution' => ['labels' => [], 'orders' => [], 'revenue' => []],
            'topProducts' => [],
            'topCustomers' => [],
            'inventoryHealth' => ['total' => 0, 'inStock' => 0, 'outOfStock' => 0, 'stockValue' => 0, 'inStockPct' => 0],
            'lowStockAlerts' => [],
            'syncHealth' => ['requests' => 0, 'successRate' => 0, 'avgLatencyMs' => 0, 'failures' => 0, 'unresolvedFailures' => 0, 'lastSyncAt' => null],
        ];
    }
}
