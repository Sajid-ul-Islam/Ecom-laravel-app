<?php

namespace Tests\Feature;

use App\Models\WooOrder;
use App\Models\WooOrderItem;
use App\Models\WooProduct;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! in_array('mysql', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('MySQL PDO driver not available on this system.');
        }

        parent::setUp();
    }

    public function test_metrics_aggregate_real_rows_for_all_time(): void
    {
        $order = WooOrder::create([
            'woo_id' => 1001,
            'number' => '1001',
            'status' => 'completed',
            'currency' => 'BDT',
            'subtotal' => '1000.00',
            'total' => '1100.00',
            'shipping_total' => '100.00',
            'payment_method_title' => 'Cash on delivery',
            'customer_email' => 'buyer@example.com',
            'billing' => ['state' => 'BD-13'],
            'woo_created_at' => now()->subDay(),
        ]);

        WooOrderItem::create([
            'woo_order_id' => $order->id,
            'woo_id' => 2001,
            'name' => 'Denim Jeans',
            'sku' => 'DN-001',
            'quantity' => 2,
            'total' => '1000.00',
        ]);

        WooProduct::create([
            'woo_id' => 3001,
            'name' => 'Denim Jeans',
            'sku' => 'DN-001',
            'status' => 'publish',
            'stock_status' => 'instock',
            'stock_quantity' => 10,
            'price' => '500.00',
            'raw_payload' => ['categories' => [['name' => 'JEANS']]],
        ]);

        $metrics = app(AnalyticsService::class)->metrics('all');

        $this->assertSame(1, $metrics['kpis']['orders']);
        $this->assertEqualsWithDelta(1100.0, $metrics['kpis']['revenue'], 0.01);
        $this->assertEqualsWithDelta(1000.0, $metrics['kpis']['netRevenue'], 0.01);
        $this->assertEqualsWithDelta(1100.0, $metrics['kpis']['aov'], 0.01);
        $this->assertSame(2, $metrics['kpis']['units']);
        $this->assertSame(1, $metrics['kpis']['customers']);
        $this->assertContains('JEANS', $metrics['categoryShare']['labels']);
        $this->assertCount(1, $metrics['geoBreakdown']['labels']);
        $this->assertSame('Dhaka', $metrics['geoBreakdown']['labels'][0]);
    }

    public function test_dashboard_page_and_api_render(): void
    {
        $this->get(route('admin.analytics'))
            ->assertOk()
            ->assertSee('Executive BI Dashboard');

        $this->get(route('admin.api.metrics', ['range' => 'all']))
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_export_returns_csv_stream(): void
    {
        $response = $this->get(route('admin.analytics.export', ['range' => '30days']));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }
}
