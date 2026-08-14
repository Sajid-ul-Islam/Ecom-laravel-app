<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WooApiLog extends Model
{
    protected $fillable = [
        'method',
        'endpoint',
        'query',
        'status_code',
        'response_time_ms',
        'success',
        'error_message',
    ];

    protected $casts = [
        'query' => 'array',
        'status_code' => 'integer',
        'response_time_ms' => 'integer',
        'success' => 'boolean',
    ];
}
