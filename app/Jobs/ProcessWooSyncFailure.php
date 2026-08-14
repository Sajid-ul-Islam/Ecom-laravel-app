<?php

namespace App\Jobs;

use App\Models\WooSyncFailure;
use App\Services\WooCommerceSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWooSyncFailure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $failureId)
    {
        $this->onQueue('woo-dead-letter');
    }

    /**
     * Execute the job.
     */
    public function handle(WooCommerceSyncService $syncService): void
    {
        $failure = WooSyncFailure::query()->unresolved()->find($this->failureId);

        if (! $failure) {
            return;
        }

        try {
            $payload = $failure->payload ?? [];

            match ($failure->entity_type) {
                'order' => $syncService->syncOrder($payload),
                'stock' => $syncService->syncStockItem($payload),
                default => $syncService->syncProduct($payload),
            };

            $failure->markResolved();

            Log::channel('woocommerce')->info("Resolved WooCommerce sync failure #{$failure->id}", [
                'entity_type' => $failure->entity_type,
                'woo_id' => $failure->woo_id,
            ]);
        } catch (Throwable $exception) {
            WooSyncFailure::record($failure->entity_type, $failure->payload ?? [], $exception);

            Log::channel('woocommerce')->error("Dead-letter retry failed for WooSyncFailure #{$failure->id}", [
                'entity_type' => $failure->entity_type,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
