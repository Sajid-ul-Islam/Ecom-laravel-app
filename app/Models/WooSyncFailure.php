<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class WooSyncFailure extends Model
{
    protected $fillable = [
        'entity_type',
        'woo_id',
        'payload',
        'error_message',
        'error_context',
        'attempts',
        'last_attempted_at',
        'resolved_at',
    ];

    protected $casts = [
        'woo_id' => 'integer',
        'payload' => 'array',
        'error_context' => 'array',
        'attempts' => 'integer',
        'last_attempted_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }

    public static function record(string $entityType, array $payload, Throwable $exception): self
    {
        $wooId = isset($payload['id']) ? (int) $payload['id'] : null;

        $existing = static::query()
            ->unresolved()
            ->where('entity_type', $entityType)
            ->when($wooId, fn (Builder $query) => $query->where('woo_id', $wooId))
            ->latest('id')
            ->first();

        if ($existing) {
            $existing->update([
                'payload' => $payload,
                'error_message' => $exception->getMessage(),
                'error_context' => static::contextFrom($exception, $payload),
                'attempts' => $existing->attempts + 1,
                'last_attempted_at' => now(),
            ]);

            return $existing->fresh();
        }

        return static::create([
            'entity_type' => $entityType,
            'woo_id' => $wooId,
            'payload' => $payload,
            'error_message' => $exception->getMessage(),
            'error_context' => static::contextFrom($exception, $payload),
            'attempts' => 1,
            'last_attempted_at' => now(),
        ]);
    }

    public function markResolved(): void
    {
        $this->forceFill(['resolved_at' => now()])->save();
    }

    protected static function contextFrom(Throwable $exception, array $payload): array
    {
        return [
            'exception' => $exception::class,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'status_code' => method_exists($exception, 'statusCode') || isset($exception->statusCode)
                ? ($exception->statusCode ?? null)
                : null,
            'payload_keys' => array_keys($payload),
        ];
    }
}
