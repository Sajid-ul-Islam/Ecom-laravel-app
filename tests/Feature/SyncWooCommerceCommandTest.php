<?php

namespace Tests\Feature;

use App\Enums\WooSyncStatus;
use App\Models\WooOrder;
use App\Models\WooPriceHistory;
use App\Models\WooProduct;
use App\Models\WooSyncFailure;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PDO;
use Tests\TestCase;

class SyncWooCommerceCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (empty(PDO::getAvailableDrivers())) {
            $this->markTestSkipped('PDO database driver (pdo_sqlite / pdo_mysql) is not installed in the PHP environment.');
        }

        parent::setUp();
    }

    public function test_sync_command_syncs_products_and_handles_insert_update_archive(): void
    {
        $existing = WooProduct::create([
            'woo_id' => 10,
            'name' => 'Old Product Name',
            'sku' => 'PROD-10',
            'price' => '50.00',
            'regular_price' => '50.00',
            'stock_quantity' => 10,
            'sync_status' => WooSyncStatus::Synced,
        ]);

        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/products?page=1&per_page=100' => Http::response([
                [
                    'id' => 10,
                    'name' => 'Updated Product Name',
                    'sku' => 'PROD-10',
                    'status' => 'publish',
                    'price' => '75.00',
                    'regular_price' => '75.00',
                    'stock_quantity' => 20,
                    'manage_stock' => true,
                    'date_created_gmt' => '2026-01-01T10:00:00',
                    'date_modified_gmt' => '2026-08-14T10:00:00',
                ],
                [
                    'id' => 20,
                    'name' => 'New Product',
                    'sku' => 'PROD-20',
                    'status' => 'publish',
                    'price' => '100.00',
                    'regular_price' => '100.00',
                    'stock_quantity' => 5,
                    'manage_stock' => true,
                    'date_created_gmt' => '2026-08-14T10:00:00',
                ],
                [
                    'id' => 30,
                    'name' => 'Trashed Product',
                    'sku' => 'PROD-30',
                    'status' => 'trash',
                    'price' => '10.00',
                    'date_created_gmt' => '2026-01-01T10:00:00',
                ],
            ], 200, ['X-WP-TotalPages' => '1']),
            'https://deencommerce.com/wp-json/wc/v3/orders?*' => Http::response([], 200, ['X-WP-TotalPages' => '1']),
        ]);

        $this->artisan('sync:woocommerce', ['--type' => 'products'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('woo_products', [
            'woo_id' => 10,
            'name' => 'Updated Product Name',
            'price' => 75.00,
            'stock_quantity' => 20,
        ]);

        $this->assertDatabaseHas('woo_products', [
            'woo_id' => 20,
            'name' => 'New Product',
            'price' => 100.00,
        ]);

        $this->assertDatabaseHas('woo_price_histories', [
            'woo_id' => 10,
            'old_price' => 50.00,
            'new_price' => 75.00,
        ]);

        $trashed = WooProduct::withTrashed()->where('woo_id', 30)->first();
        $this->assertNotNull($trashed);
        $this->assertTrue($trashed->trashed());
        $this->assertEquals(WooSyncStatus::Archived, $trashed->sync_status);
    }

    public function test_sync_command_orders_only_syncs_processing_and_completed_orders(): void
    {
        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/orders?*' => Http::response([
                [
                    'id' => 501,
                    'number' => 'ORD-501',
                    'status' => 'processing',
                    'total' => '150.00',
                    'currency' => 'BDT',
                    'billing' => ['email' => 'customer@example.com'],
                    'line_items' => [
                        [
                            'id' => 901,
                            'product_id' => 10,
                            'name' => 'Sample Item',
                            'quantity' => 2,
                            'price' => '75.00',
                            'total' => '150.00',
                        ],
                    ],
                ],
                [
                    'id' => 502,
                    'number' => 'ORD-502',
                    'status' => 'completed',
                    'total' => '300.00',
                    'currency' => 'BDT',
                    'line_items' => [],
                ],
            ], 200, ['X-WP-TotalPages' => '1']),
        ]);

        $this->artisan('sync:woocommerce', ['--type' => 'orders'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('woo_orders', [
            'woo_id' => 501,
            'status' => 'processing',
            'total' => 150.00,
        ]);

        $this->assertDatabaseHas('woo_orders', [
            'woo_id' => 502,
            'status' => 'completed',
            'total' => 300.00,
        ]);

        $this->assertDatabaseHas('woo_order_items', [
            'woo_id' => 901,
            'name' => 'Sample Item',
            'quantity' => 2,
        ]);
    }

    public function test_sync_command_stock_updates_local_product_inventory(): void
    {
        Product::forceCreate([
            'name' => 'Local Shirt',
            'sku' => 'SHIRT-1',
            'price' => 20.00,
            'stock_quantity' => 5,
        ]);

        WooProduct::create([
            'woo_id' => 88,
            'name' => 'Local Shirt',
            'sku' => 'SHIRT-1',
            'price' => '20.00',
            'stock_quantity' => 5,
            'sync_status' => WooSyncStatus::Synced,
        ]);

        Http::fake([
            'https://deencommerce.com/wp-json/wc/v3/products?*_fields=*' => Http::response([
                [
                    'id' => 88,
                    'sku' => 'SHIRT-1',
                    'status' => 'publish',
                    'stock_quantity' => 45,
                    'stock_status' => 'instock',
                    'manage_stock' => true,
                    'price' => '25.00',
                ],
            ], 200, ['X-WP-TotalPages' => '1']),
        ]);

        $this->artisan('sync:woocommerce', ['--type' => 'stock'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('woo_products', [
            'woo_id' => 88,
            'stock_quantity' => 45,
            'price' => 25.00,
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'SHIRT-1',
            'stock_quantity' => 45,
            'price' => 25.00,
        ]);
    }

    public function test_retry_failed_option_retries_dead_letter_failures(): void
    {
        $failure = WooSyncFailure::create([
            'entity_type' => 'product',
            'woo_id' => 777,
            'payload' => [
                'id' => 777,
                'name' => 'Recovered Product',
                'sku' => 'REC-777',
                'status' => 'publish',
                'price' => '99.00',
            ],
            'error_message' => 'Temporary network error',
            'attempts' => 1,
        ]);

        $this->artisan('sync:woocommerce', ['--retry-failed' => true])
            ->assertExitCode(0);

        $this->assertNotNull($failure->fresh()->resolved_at);

        $this->assertDatabaseHas('woo_products', [
            'woo_id' => 777,
            'name' => 'Recovered Product',
        ]);
    }
}
