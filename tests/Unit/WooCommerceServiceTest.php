<?php

namespace Tests\Unit;

use App\Exceptions\WooCommerceException;
use App\Models\WooApiLog;
use App\Services\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PDO;

class WooCommerceServiceTest extends \Tests\TestCase
{
    use RefreshDatabase;

    protected WooCommerceService $service;

    protected function setUp(): void
    {
        if (empty(PDO::getAvailableDrivers())) {
            $this->markTestSkipped('PDO database driver (pdo_sqlite / pdo_mysql) is not installed in the PHP environment.');
        }

        parent::setUp();
        $this->service = new WooCommerceService();
    }

    public function test_paginate_fetches_all_pages_using_x_wp_total_pages_header(): void
    {
        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/products?page=1&per_page=100' => Http::response(
                [['id' => 101, 'name' => 'Product 1']],
                200,
                ['X-WP-TotalPages' => '2']
            ),
            'https://deencommerce.com/wp-json/wc/v3/products?page=2&per_page=100' => Http::response(
                [['id' => 102, 'name' => 'Product 2']],
                200,
                ['X-WP-TotalPages' => '2']
            ),
        ]);

        $generator = $this->service->paginate('products');
        $items = iterator_to_array($generator);

        $this->assertCount(2, $items);
        $this->assertEquals(101, $items[0]['id']);
        $this->assertEquals(102, $items[1]['id']);
    }

    public function test_get_product_caches_response(): void
    {
        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/products/50' => Http::response(
                ['id' => 50, 'name' => 'Cached Product', 'price' => '100.00'],
                200
            ),
        ]);

        $product1 = $this->service->getProduct(50, true);
        $this->assertEquals('Cached Product', $product1['name']);

        $this->assertTrue(Cache::has('woo:product:50'));

        $product2 = $this->service->getProduct(50, true);
        $this->assertEquals('Cached Product', $product2['name']);

        Http::assertSentCount(1);
    }

    public function test_requests_are_logged_to_woo_api_logs_table(): void
    {
        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/orders*' => Http::response(
                [['id' => 201, 'status' => 'processing']],
                200
            ),
        ]);

        $this->service->get('orders', ['status' => 'processing']);

        $this->assertDatabaseHas('woo_api_logs', [
            'method' => 'GET',
            'endpoint' => 'orders',
            'status_code' => 200,
            'success' => true,
        ]);

        $log = WooApiLog::first();
        $this->assertNotNull($log->response_time_ms);
        $this->assertIsInt($log->response_time_ms);
    }

    public function test_exponential_backoff_calculation(): void
    {
        config([
            'woocommerce.retry.base_ms' => 1000,
            'woocommerce.retry.max_ms' => 30000,
        ]);

        $this->assertEquals(1000, $this->service->backoffMilliseconds(1));
        $this->assertEquals(2000, $this->service->backoffMilliseconds(2));
        $this->assertEquals(4000, $this->service->backoffMilliseconds(3));
        $this->assertEquals(8000, $this->service->backoffMilliseconds(4));
        $this->assertEquals(30000, $this->service->backoffMilliseconds(10));
    }

    public function test_http_exception_throws_woocommerce_exception_and_logs_error(): void
    {
        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/products/999' => Http::response(
                ['code' => 'woocommerce_rest_product_invalid_id', 'message' => 'Invalid ID.'],
                404
            ),
        ]);

        $this->expectException(WooCommerceException::class);
        $this->expectExceptionCode(404);

        try {
            $this->service->get('products/999');
        } finally {
            $this->assertDatabaseHas('woo_api_logs', [
                'method' => 'GET',
                'endpoint' => 'products/999',
                'status_code' => 404,
                'success' => false,
            ]);
        }
    }
}
