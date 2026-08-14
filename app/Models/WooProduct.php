<?php

namespace App\Models;

use App\Enums\WooSyncStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WooProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'woo_id',
        'name',
        'slug',
        'sku',
        'type',
        'status',
        'permalink',
        'description',
        'short_description',
        'price',
        'regular_price',
        'sale_price',
        'stock_quantity',
        'stock_status',
        'manage_stock',
        'featured_image',
        'raw_payload',
        'sync_status',
        'last_synced_at',
        'woo_created_at',
        'woo_updated_at',
    ];

    protected $casts = [
        'woo_id' => 'integer',
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'raw_payload' => 'array',
        'sync_status' => WooSyncStatus::class,
        'last_synced_at' => 'datetime',
        'woo_created_at' => 'datetime',
        'woo_updated_at' => 'datetime',
    ];

    public function priceHistories(): HasMany
    {
        return $this->hasMany(WooPriceHistory::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'publish';
    }
}
