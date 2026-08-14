<?php

namespace App\Services;

use App\Enums\WooSyncStatus;
use App\Exceptions\WooCommerceException;
use App\Jobs\ProcessWooSyncFailure;
use App\Models\WooOrder;
use App\Models\WooOrderItem;
use App\Models\WooPriceHistory;
use App\Models\WooProduct;
use App\Models\WooSyncFailure;
use App\Notifications\WooCommerceSyncFailed;
use App\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Throwable;

class WooCommerceSyncService
{
    public function __construct(protected WooCommerceService $api)
    {
    }

    public function syncAll(): array
    {
        $products = $this->syncProducts();
        $orders = $this->syncOrders();

        return [
            'products' => $products,
            'orders' => $orders,
        ];
    }

    public function syncProducts(): array
    {
        $stats = $this->emptyStats();

        try {
            foreach ($this->api->getProducts() as $payload) {
                $this->runItem('product', $payload, $stats, function (array $item) use (&$stats) {
                    $result = $this->syncProduct($item);
                    $stats[$result]++;
                });
            }
            \Illuminate\Support\Facades\Cache::forget('deen_hero_slideshow_products');
            \Illuminate\Support\Facades\Cache::forget('deen_store_all_categories_list');
            \Illuminate\Support\Facades\Cache::forget('deen_all_categories_page');
        } catch (WooCommerceException $exception) {
            $this->handleCriticalFailure('Product sync failed', $exception);
            throw $exception;
        }

        return $stats;
    }


    public function syncOrders(): array
    {
        $stats = $this->emptyStats();
        $allowed = config('woocommerce.order_statuses', ['processing', 'completed']);

        try {
            foreach ($this->api->getOrders() as $payload) {
                $status = $payload['status'] ?? null;
                if (! in_array($status, $allowed, true)) {
                    $stats['skipped']++;
                    continue;
                }

                $this->runItem('order', $payload, $stats, function (array $item) use (&$stats) {
                    $this->syncOrder($item);
                    $stats['synced']++;
                });
            }
        } catch (WooCommerceException $exception) {
            $this->handleCriticalFailure('Order sync failed', $exception);
            throw $exception;
        }

        return $stats;
    }

    public function syncStock(): array
    {
        $stats = $this->emptyStats();

        try {
            foreach ($this->api->getProducts([
                '_fields' => 'id,sku,status,stock_quantity,stock_status,manage_stock,price,regular_price,sale_price,date_modified_gmt',
            ]) as $payload) {
                $this->runItem('stock', $payload, $stats, function (array $item) use (&$stats) {
                    $this->syncStockItem($item);
                    $stats['synced']++;
                });
            }
        } catch (WooCommerceException $exception) {
            $this->handleCriticalFailure('Stock sync failed', $exception);
            throw $exception;
        }

        return $stats;
    }

    public function retryFailed(?int $limit = null): array
    {
        $stats = $this->emptyStats();

        $query = WooSyncFailure::query()->unresolved()->orderBy('id');
        if ($limit) {
            $query->limit($limit);
        }

        foreach ($query->get() as $failure) {
            try {
                $payload = $failure->payload ?? [];

                match ($failure->entity_type) {
                    'order' => $this->syncOrder($payload),
                    'stock' => $this->syncStockItem($payload),
                    default => $this->syncProduct($payload),
                };

                $failure->markResolved();
                $stats['synced']++;
            } catch (Throwable $exception) {
                WooSyncFailure::record($failure->entity_type, $failure->payload ?? [], $exception);
                $stats['failed']++;
            }
        }

        return $stats;
    }

    public function syncProduct(array $payload): string
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId < 1) {
            throw new WooCommerceException('Product payload is missing a WooCommerce id.', 422, ['payload' => $payload]);
        }

        $product = WooProduct::withTrashed()->where('woo_id', $wooId)->first()
            ?? new WooProduct(['woo_id' => $wooId]);

        $wasExisting = $product->exists;
        $oldPrices = [
            'price' => $product->price,
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
        ];

        $status = (string) ($payload['status'] ?? 'publish');
        $action = $wasExisting ? 'updated' : 'created';

        $product->fill($this->productAttributes($payload));

        if ($status === 'trash') {
            $product->sync_status = WooSyncStatus::Archived;
            $product->last_synced_at = now();
            $product->save();

            if (! $product->trashed()) {
                $product->delete();
            }

            return 'archived';
        }

        if ($product->trashed()) {
            $product->restore();
            $action = 'updated';
        }

        $product->sync_status = WooSyncStatus::Synced;
        $product->last_synced_at = now();
        $product->save();

        if ($wasExisting) {
            $this->recordPriceChange($product, $oldPrices);
        }

        $this->syncLocalInventory($product);

        return $action;
    }

    public function syncOrder(array $payload): WooOrder
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId < 1) {
            throw new WooCommerceException('Order payload is missing a WooCommerce id.', 422, ['payload' => $payload]);
        }

        $allowed = config('woocommerce.order_statuses', ['processing', 'completed']);
        $status = $payload['status'] ?? null;
        if (! in_array($status, $allowed, true)) {
            throw new WooCommerceException(
                "Order #{$wooId} has status [{$status}] and will not be synced.",
                422,
                ['woo_id' => $wooId, 'status' => $status]
            );
        }

        $order = WooOrder::query()->firstOrNew(['woo_id' => $wooId]);
        $order->fill([
            'number' => $payload['number'] ?? $payload['id'] ?? null,
            'status' => $status,
            'currency' => $payload['currency'] ?? null,
            'subtotal' => $this->money($this->lineItemsSum($payload['line_items'] ?? [], 'subtotal')) ?? $this->money($payload['total'] ?? null),
            'total_tax' => $this->money($payload['total_tax'] ?? null),
            'shipping_total' => $this->money($payload['shipping_total'] ?? null),
            'discount_total' => $this->money($payload['discount_total'] ?? null),
            'total' => $this->money($payload['total'] ?? null),
            'payment_method' => $payload['payment_method'] ?? null,
            'payment_method_title' => $payload['payment_method_title'] ?? null,
            'customer_id' => $payload['customer_id'] ?? null,
            'customer_email' => $payload['billing']['email'] ?? null,
            'customer_note' => $payload['customer_note'] ?? null,
            'billing' => $payload['billing'] ?? null,
            'shipping' => $payload['shipping'] ?? null,
            'raw_payload' => $payload,
            'sync_status' => WooSyncStatus::Synced,
            'last_synced_at' => now(),
            'woo_created_at' => $this->wooDate($payload['date_created_gmt'] ?? $payload['date_created'] ?? null),
            'woo_updated_at' => $this->wooDate($payload['date_modified_gmt'] ?? $payload['date_modified'] ?? null),
            'woo_completed_at' => $this->wooDate($payload['date_completed_gmt'] ?? $payload['date_completed'] ?? null),
        ]);
        $order->save();

        $this->syncOrderItems($order, $payload['line_items'] ?? []);

        return $order;
    }

    public function syncStockItem(array $payload): WooProduct
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId < 1) {
            throw new WooCommerceException('Stock payload is missing a WooCommerce id.', 422, ['payload' => $payload]);
        }

        $product = WooProduct::withTrashed()->where('woo_id', $wooId)->first();
        if (! $product) {
            $this->syncProduct($payload);
            $product = WooProduct::withTrashed()->where('woo_id', $wooId)->firstOrFail();
        }

        $oldPrices = [
            'price' => $product->price,
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
        ];

        $product->fill([
            'sku' => $payload['sku'] ?? $product->sku,
            'status' => $payload['status'] ?? $product->status,
            'stock_quantity' => $payload['stock_quantity'] ?? $product->stock_quantity,
            'stock_status' => $payload['stock_status'] ?? $product->stock_status,
            'manage_stock' => (bool) ($payload['manage_stock'] ?? $product->manage_stock),
            'price' => $this->money($payload['price'] ?? $product->price),
            'regular_price' => $this->money($payload['regular_price'] ?? $product->regular_price),
            'sale_price' => $this->money($payload['sale_price'] ?? $product->sale_price),
            'woo_updated_at' => $this->wooDate($payload['date_modified_gmt'] ?? $payload['date_modified'] ?? null) ?? $product->woo_updated_at,
            'sync_status' => WooSyncStatus::Synced,
            'last_synced_at' => now(),
        ]);
        $product->save();

        $this->recordPriceChange($product, $oldPrices);
        $this->syncLocalInventory($product);

        return $product;
    }

    protected function runItem(string $entityType, array $payload, array &$stats, callable $callback): void
    {
        try {
            $callback($payload);
        } catch (Throwable $exception) {
            $stats['failed']++;
            $failure = WooSyncFailure::record($entityType, $payload, $exception);

            Log::channel('woocommerce')->error("Failed to sync WooCommerce {$entityType}", [
                'woo_id' => $payload['id'] ?? null,
                'error' => $exception->getMessage(),
                'exception' => $exception::class,
            ]);

            ProcessWooSyncFailure::dispatch($failure->id)->onQueue('woo-dead-letter');
        }
    }

    protected function productAttributes(array $payload): array
    {
        $image = $payload['images'][0]['src'] ?? null;

        return [
            'woo_id' => (int) $payload['id'],
            'name' => $payload['name'] ?? null,
            'slug' => $payload['slug'] ?? null,
            'sku' => $payload['sku'] ?? null,
            'type' => $payload['type'] ?? null,
            'status' => $payload['status'] ?? null,
            'permalink' => $payload['permalink'] ?? null,
            'description' => $payload['description'] ?? null,
            'short_description' => $payload['short_description'] ?? null,
            'price' => $this->money($payload['price'] ?? null),
            'regular_price' => $this->money($payload['regular_price'] ?? null),
            'sale_price' => $this->money($payload['sale_price'] ?? null),
            'stock_quantity' => $payload['stock_quantity'] ?? null,
            'stock_status' => $payload['stock_status'] ?? null,
            'manage_stock' => (bool) ($payload['manage_stock'] ?? false),
            'featured_image' => $image,
            'raw_payload' => $payload,
            'woo_created_at' => $this->wooDate($payload['date_created_gmt'] ?? $payload['date_created'] ?? null),
            'woo_updated_at' => $this->wooDate($payload['date_modified_gmt'] ?? $payload['date_modified'] ?? null),
        ];
    }

    protected function recordPriceChange(WooProduct $product, array $oldPrices): void
    {
        $changed = $this->priceChanged($oldPrices['price'] ?? null, $product->price)
            || $this->priceChanged($oldPrices['regular_price'] ?? null, $product->regular_price)
            || $this->priceChanged($oldPrices['sale_price'] ?? null, $product->sale_price);

        if (! $changed) {
            return;
        }

        WooPriceHistory::create([
            'woo_product_id' => $product->id,
            'woo_id' => $product->woo_id,
            'old_price' => $oldPrices['price'] ?? null,
            'new_price' => $product->price,
            'old_regular_price' => $oldPrices['regular_price'] ?? null,
            'new_regular_price' => $product->regular_price,
            'old_sale_price' => $oldPrices['sale_price'] ?? null,
            'new_sale_price' => $product->sale_price,
            'changed_at' => now(),
        ]);
    }

    protected function syncLocalInventory(WooProduct $product): void
    {
        if (! $product->sku || ! Schema::hasTable('products')) {
            return;
        }

        try {
            $updates = ['stock_quantity' => $product->stock_quantity ?? 0];
            if ($product->price !== null) {
                $updates['price'] = $product->price;
            }

            Product::query()->where('sku', $product->sku)->update($updates);
        } catch (Throwable $exception) {
            Log::channel('woocommerce')->warning('Could not update local product inventory from WooCommerce', [
                'sku' => $product->sku,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    protected function syncOrderItems(WooOrder $order, array $lineItems): void
    {
        $seen = [];

        foreach ($lineItems as $item) {
            $wooId = (int) ($item['id'] ?? 0);
            if ($wooId < 1) {
                continue;
            }

            $seen[] = $wooId;

            WooOrderItem::query()->updateOrCreate(
                ['woo_id' => $wooId],
                [
                    'woo_order_id' => $order->id,
                    'woo_product_id' => $item['product_id'] ?? null,
                    'woo_variation_id' => $item['variation_id'] ?? null,
                    'name' => $item['name'] ?? null,
                    'sku' => $item['sku'] ?? null,
                    'quantity' => $item['quantity'] ?? 0,
                    'price' => $this->money($item['price'] ?? null),
                    'subtotal' => $this->money($item['subtotal'] ?? null),
                    'total' => $this->money($item['total'] ?? null),
                    'total_tax' => $this->money($item['total_tax'] ?? null),
                ]
            );
        }

        if ($seen !== []) {
            $order->items()->whereNotIn('woo_id', $seen)->delete();
        }
    }

    protected function handleCriticalFailure(string $title, WooCommerceException $exception): void
    {
        Log::channel('woocommerce')->critical($title, [
            'message' => $exception->getMessage(),
            'status_code' => $exception->statusCode,
            'context' => $exception->context,
        ]);

        $email = config('woocommerce.notify_email');
        if (! $email) {
            return;
        }

        try {
            Notification::route('mail', $email)
                ->notify(new WooCommerceSyncFailed($title, $exception->getMessage(), $exception->context));
        } catch (Throwable $notificationException) {
            Log::channel('woocommerce')->error('Failed to send WooCommerce critical failure notification', [
                'error' => $notificationException->getMessage(),
            ]);
        }
    }

    protected function money(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }

    protected function priceChanged(mixed $old, mixed $new): bool
    {
        return $this->money($old) !== $this->money($new);
    }

    protected function wooDate(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }

        return (string) $value;
    }

    protected function lineItemsSum(array $items, string $field): float
    {
        return array_reduce($items, function (float $carry, array $item) use ($field) {
            return $carry + (float) ($item[$field] ?? 0);
        }, 0.0);
    }

    protected function emptyStats(): array
    {
        return [
            'created' => 0,
            'updated' => 0,
            'archived' => 0,
            'synced' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];
    }
}
