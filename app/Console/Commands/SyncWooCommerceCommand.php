<?php

namespace App\Console\Commands;

use App\Services\WooCommerceSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncWooCommerceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:woocommerce
                            {--type=all : Specific entity to sync: all, products, orders, stock}
                            {--retry-failed : Retry failed records from the dead-letter queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync WooCommerce products, orders, and stock via REST API';

    /**
     * Execute the console command.
     */
    public function handle(WooCommerceSyncService $syncService): int
    {
        $this->info('Starting WooCommerce synchronization...');
        $type = strtolower((string) $this->option('type'));

        try {
            if ($this->option('retry-failed')) {
                $this->info('Retrying unresolved WooCommerce sync failures...');
                $stats = $syncService->retryFailed();
                $this->displayStats('Failed Retries', $stats);
                return self::SUCCESS;
            }

            match ($type) {
                'products' => $this->displayStats('Products', $syncService->syncProducts()),
                'orders' => $this->displayStats('Orders', $syncService->syncOrders()),
                'stock' => $this->displayStats('Stock', $syncService->syncStock()),
                'all' => $this->syncAll($syncService),
                default => $this->error("Invalid sync type [{$type}]. Allowed: all, products, orders, stock."),
            };

            $this->info('WooCommerce synchronization completed successfully.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('WooCommerce synchronization encountered a critical error: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }

    protected function syncAll(WooCommerceSyncService $syncService): void
    {
        $this->info('Syncing WooCommerce Products...');
        $this->displayStats('Products', $syncService->syncProducts());

        $this->info('Syncing WooCommerce Orders...');
        $this->displayStats('Orders', $syncService->syncOrders());

        $this->info('Syncing WooCommerce Stock...');
        $this->displayStats('Stock', $syncService->syncStock());
    }

    protected function displayStats(string $title, array $stats): void
    {
        $headers = ['Created', 'Updated', 'Archived', 'Synced', 'Skipped', 'Failed'];
        $rows = [[
            $stats['created'] ?? 0,
            $stats['updated'] ?? 0,
            $stats['archived'] ?? 0,
            $stats['synced'] ?? 0,
            $stats['skipped'] ?? 0,
            $stats['failed'] ?? 0,
        ]];

        $this->line("<comment>=== {$title} Summary ===</comment>");
        $this->table($headers, $rows);
    }
}
