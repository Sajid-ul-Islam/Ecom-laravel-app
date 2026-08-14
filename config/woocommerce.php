<?php

return [

    'url' => rtrim((string) env('WOO_URL', 'https://deencommerce.com'), '/'),

    'consumer_key' => env('WOO_CONSUMER_KEY'),

    'consumer_secret' => env('WOO_CONSUMER_SECRET'),

    /*
    | Sync interval in minutes (used by the scheduler).
    */
    'sync_interval' => (int) env('WOO_SYNC_INTERVAL', 5),

    'timeout' => (int) env('WOO_TIMEOUT', 30),

    'connect_timeout' => (int) env('WOO_CONNECT_TIMEOUT', 10),

    'per_page' => (int) env('WOO_PER_PAGE', 100),

    'retry' => [
        'times' => (int) env('WOO_RETRY_TIMES', 4),
        'base_ms' => (int) env('WOO_RETRY_BASE_MS', 1000),
        'max_ms' => (int) env('WOO_RETRY_MAX_MS', 30000),
    ],

    'cache_ttl' => (int) env('WOO_CACHE_TTL', 60),

    'order_statuses' => ['processing', 'completed'],

    'notify_email' => env('WOO_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS')),

    'version' => env('WOO_API_VERSION', 'wc/v3'),

];
