# AGENTS.md — Deen Commerce Developer & AI Agent Context Guide

This document defines the architecture, conventions, environment configurations, and guidelines for AI agents and developers working on **Deen Commerce** (`https://deencommerce.com`).

---

## 1. Project Overview & Architecture

**Deen Commerce** is a modern retail fashion & urban apparel e-commerce platform built on Laravel 10 (PHP 8.3) and integrated live with the WooCommerce REST API (`wc/v3`).

### Core Technology Stack
- **Framework**: Laravel 10 (`laravel/framework: ^10.0`)
- **PHP Version**: `^8.1` / `8.3`
- **HTTP Client**: `Illuminate\Support\Facades\Http` (Guzzle 7)
- **Styling**: Vanilla CSS design system (`public/css/deen-commerce-store.css` and `public/css/woocommerce-dashboard.css`)
- **Frontend Views**: Blade templates with Bootstrap 5 and FontAwesome 6 icons.

---

## 2. Environment Configuration Reference

Place the following environment variables in `.env` (and reference placeholders in `.env.example` / `.env.testing`):

```ini
# WooCommerce REST API Credentials
WOO_URL=https://deencommerce.com
WOO_CONSUMER_KEY=ck_954a53b921ceb29ff572460856193d9b57c94c23
WOO_CONSUMER_SECRET=cs_e3c0de58c7b1a8ff116215f5241c192f4b832e49
WOO_SYNC_INTERVAL=5
WOO_PER_PAGE=100
WOO_TIMEOUT=30
WOO_CONNECT_TIMEOUT=10
WOO_CACHE_TTL=60
```

---

## 3. Directory & File Structure Map

```
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── SyncWooCommerceCommand.php   # php artisan sync:woocommerce
│   │   └── Kernel.php                      # Scheduled sync every 5 mins
│   ├── Enums/
│   │   └── WooSyncStatus.php               # Synced, Pending, Archived
│   ├── Exceptions/
│   │   └── WooCommerceException.php        # Custom API exception
│   ├── Http/
│   │   └── Controllers/
│   │       ├── DeenCommerceStoreController.php  # Retail storefront, catalog, product landing, checkout
│   │       ├── WooCommerceDashboardController.php# Integration Hub & live sync dashboard
│   │       └── CategoryController.php       # Categories controller
│   ├── Jobs/
│   │   └── ProcessWooSyncFailure.php       # Dead-letter queue job (woo-dead-letter)
│   ├── Models/
│   │   ├── WooProduct.php                  # Products with soft-deletes
│   │   ├── WooOrder.php                    # Orders with status filter (processing/completed)
│   │   ├── WooOrderItem.php                # Order line items
│   │   ├── WooPriceHistory.php             # Historical price tracking
│   │   ├── WooApiLog.php                   # Request latency & status logs
│   │   └── WooSyncFailure.php              # Failed sync records
│   ├── Notifications/
│   │   └── WooCommerceSyncFailed.php       # Critical failure alert email
│   └── Services/
│       ├── WooCommerceService.php          # REST API client (exponential retries & pagination)
│       └── WooCommerceSyncService.php      # Data sync, upserts, stock updates & price tracking
├── config/
│   ├── logging.php                         # Configured 'woocommerce' log channel
│   └── woocommerce.php                     # Configuration options
├── database/migrations/
│   ├── 2026_08_14_000001_create_woo_products_table.php
│   ├── 2026_08_14_000002_create_woo_orders_table.php
│   ├── 2026_08_14_000003_create_woo_order_items_table.php
│   ├── 2026_08_14_000004_create_woo_price_histories_table.php
│   ├── 2026_08_14_000005_create_woo_api_logs_table.php
│   └── 2026_08_14_000006_create_woo_sync_failures_table.php
├── public/
│   ├── css/
│   │   ├── deen-commerce-store.css         # Retail storefront stylesheet
│   │   └── woocommerce-dashboard.css       # Integration Hub stylesheet
├── resources/views/
│   ├── welcome.blade.php                   # Retail storefront homepage
│   ├── layouts/app.blade.php               # Main navigation & layout wrapper
│   ├── store/
│   │   ├── categories.blade.php            # Categories directory view
│   │   ├── category.blade.php              # Category product gallery & sorting
│   │   ├── product.blade.php               # Full product landing page
│   │   ├── checkout.blade.php              # Retail checkout & payment selector
│   │   └── success.blade.php               # Order placement success receipt
│   └── woocommerce/
│       ├── dashboard.blade.php             # Integration Hub live sync UI
│       ├── products.blade.php              # Synced products management
│       ├── orders.blade.php                # Synced orders management
│       └── logs.blade.php                  # API request audit logs
├── routes/
│   └── web.php                             # Storefront & WooCommerce routes
└── tests/
    ├── Unit/WooCommerceServiceTest.php     # Service unit tests
    └── Feature/SyncWooCommerceCommandTest.php# Command feature tests
```

---

## 4. Key Web Routes & Endpoints

| Route Name | Method | Path | Controller Action | Description |
|---|---|---|---|---|
| `store.index` | `GET` | `/` | `DeenCommerceStoreController@index` | Retail Storefront Homepage |
| `store.categories` | `GET` | `/categories` | `DeenCommerceStoreController@categories` | All Categories Showcase |
| `store.category` | `GET` | `/category/{id}` | `DeenCommerceStoreController@categoryProducts` | Category Products & Sorting |
| `store.product.detail` | `GET` | `/product/{id}` | `DeenCommerceStoreController@productDetail` | Full Product Landing Page |
| `store.checkout` | `GET` | `/checkout` | `DeenCommerceStoreController@checkout` | Retail Checkout Page |
| `store.checkout.process` | `POST` | `/checkout` | `DeenCommerceStoreController@processCheckout` | Process Order & Payment |
| `store.order.success` | `GET` | `/order-success/{id}` | `DeenCommerceStoreController@orderSuccess` | Order Confirmation Receipt |
| `woocommerce.dashboard` | `GET` | `/woocommerce/dashboard` | `WooCommerceDashboardController@dashboard` | Live Integration Dashboard |
| `woocommerce.products` | `GET` | `/woocommerce/products` | `WooCommerceDashboardController@products` | Synced Products Management |
| `woocommerce.orders` | `GET` | `/woocommerce/orders` | `WooCommerceDashboardController@orders` | Synced Orders Management |
| `woocommerce.logs` | `GET` | `/woocommerce/logs` | `WooCommerceDashboardController@logs` | REST API Audit Logs |
| `woocommerce.sync` | `POST` | `/woocommerce/sync` | `WooCommerceDashboardController@triggerSync` | AJAX Live Sync Trigger |
| `woocommerce.retry-failures` | `POST` | `/woocommerce/retry-failures` | `WooCommerceDashboardController@retryFailures` | Dead-Letter Queue Retry |

---

## 5. Development & Testing Rules

1. **API Fallback Guarantee**: Always wrap API calls in `DeenCommerceStoreController` with `Cache::remember` or `try/catch` fallback blocks so storefront views render cleanly even if remote network requests time out.
2. **Exponential Backoff**: Keep retry logic configured in `WooCommerceService::client()` using `backoffMilliseconds()`.
3. **Database Drivers**: Test cases check `PDO::getAvailableDrivers()` before running `RefreshDatabase` migrations so tests pass smoothly regardless of system CLI PDO driver configuration.
4. **Artisan Command Execution**:
   ```bash
   php artisan sync:woocommerce --type=all
   php artisan sync:woocommerce --retry-failed
   ```
5. **Running Test Suite**:
   ```bash
   ./vendor/bin/phpunit
   ```
