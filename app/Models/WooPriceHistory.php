<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WooPriceHistory extends Model
{
    protected $fillable = [
        'woo_product_id',
        'woo_id',
        'old_price',
        'new_price',
        'old_regular_price',
        'new_regular_price',
        'old_sale_price',
        'new_sale_price',
        'changed_at',
    ];

    protected $casts = [
        'woo_id' => 'integer',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'old_regular_price' => 'decimal:2',
        'new_regular_price' => 'decimal:2',
        'old_sale_price' => 'decimal:2',
        'new_sale_price' => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(WooProduct::class, 'woo_product_id');
    }
}
