<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WooOrderItem extends Model
{
    protected $fillable = [
        'woo_order_id',
        'woo_id',
        'woo_product_id',
        'woo_variation_id',
        'name',
        'sku',
        'quantity',
        'price',
        'subtotal',
        'total',
        'total_tax',
    ];

    protected $casts = [
        'woo_id' => 'integer',
        'woo_product_id' => 'integer',
        'woo_variation_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'total_tax' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(WooOrder::class, 'woo_order_id');
    }
}
