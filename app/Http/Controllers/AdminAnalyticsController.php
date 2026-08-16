<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class AdminAnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analytics)
    {
    }

    public function index(Request $request): View
    {
        $range = $this->validRange($request->input('range'));
        $metrics = $this->analytics->metrics($range);

        return view('admin.analytics', compact('metrics', 'range'));
    }

    public function apiMetrics(Request $request): JsonResponse
    {
        $range = $this->validRange($request->input('range'));
        $metrics = $this->analytics->metrics($range);

        return response()->json(['success' => true, 'range' => $range, 'metrics' => $metrics]);
    }

    /**
     * Download the revenue/order daily series for the selected range as CSV.
     */
    public function export(Request $request)
    {
        $range = $this->validRange($request->input('range'));
        $metrics = $this->analytics->metrics($range);

        $filename = 'deen-bi-' . $range . '-' . now()->format('Ymd-His') . '.csv';

        $callback = function () use ($metrics) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Deen Commerce BI Export']);
            fputcsv($handle, ['Range', $metrics['label'], 'Generated', $metrics['generated_at']]);
            fputcsv($handle, []);

            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Gross Revenue (BDT)', number_format($metrics['kpis']['revenue'], 2)]);
            fputcsv($handle, ['Net Revenue (BDT)', number_format($metrics['kpis']['netRevenue'], 2)]);
            fputcsv($handle, ['Total Orders', $metrics['kpis']['orders']]);
            fputcsv($handle, ['Average Order Value (BDT)', number_format($metrics['kpis']['aov'], 2)]);
            fputcsv($handle, ['Units Sold', $metrics['kpis']['units']]);
            fputcsv($handle, ['Unique Customers', $metrics['kpis']['customers']]);
            fputcsv($handle, ['Repeat Rate (%)', $metrics['kpis']['repeatRate']]);
            fputcsv($handle, ['Discounts Given (BDT)', number_format($metrics['kpis']['discounts'], 2)]);
            fputcsv($handle, ['Shipping Collected (BDT)', number_format($metrics['kpis']['shipping'], 2)]);
            fputcsv($handle, []);

            fputcsv($handle, ['Revenue Trend', 'Current', 'Previous']);
            foreach ($metrics['revenueTrend']['labels'] as $i => $label) {
                fputcsv($handle, [$label, $metrics['revenueTrend']['current'][$i] ?? 0, $metrics['revenueTrend']['previous'][$i] ?? 0]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Payment Method', 'Orders', 'Revenue (BDT)']);
            foreach ($metrics['paymentBreakdown']['labels'] as $i => $label) {
                fputcsv($handle, [$label, $metrics['paymentBreakdown']['orders'][$i] ?? 0, $metrics['paymentBreakdown']['revenue'][$i] ?? 0]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Top Products', 'SKU', 'Units', 'Revenue (BDT)']);
            foreach ($metrics['topProducts'] as $product) {
                fputcsv($handle, [$product['name'], $product['sku'] ?? '', $product['units'], number_format($product['revenue'], 2)]);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    protected function validRange(mixed $range): string
    {
        return in_array($range, ['today', '7days', '30days', '90days', 'ytd', 'all'], true)
            ? $range
            : '30days';
    }
}
