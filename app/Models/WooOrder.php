<?php

namespace App\Models;

use App\Enums\WooSyncStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WooOrder extends Model
{
    protected $fillable = [
        'woo_id',
        'number',
        'status',
        'currency',
        'subtotal',
        'total_tax',
        'shipping_total',
        'discount_total',
        'total',
        'payment_method',
        'payment_method_title',
        'customer_id',
        'customer_email',
        'customer_note',
        'billing',
        'shipping',
        'raw_payload',
        'sync_status',
        'last_synced_at',
        'woo_created_at',
        'woo_updated_at',
        'woo_completed_at',
    ];

    protected $casts = [
        'woo_id' => 'integer',
        'subtotal' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'customer_id' => 'integer',
        'billing' => 'array',
        'shipping' => 'array',
        'raw_payload' => 'array',
        'sync_status' => WooSyncStatus::class,
        'last_synced_at' => 'datetime',
        'woo_created_at' => 'datetime',
        'woo_updated_at' => 'datetime',
        'woo_completed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WooOrderItem::class);
    }
}
