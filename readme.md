# Deen Commerce

A modern retail fashion & urban apparel e-commerce platform built on Laravel 10, integrated live with the WooCommerce REST API (`wc/v3`). The app syncs products, orders, and stock from a live WooCommerce store into a local database, then serves a custom storefront with graceful fallback when the API is unreachable.

**Live store:** [deencommerce.com](https://deencommerce.com)

---

## Features

### Retail Storefront
- **Homepage** — hero product slideshow, category grid, paginated product catalog
- **Categories** — category directory and per-category product galleries with price/date sorting
- **Product pages** — full product detail with related products and search suggestions
- **Checkout** — form-based checkout with bKash, Nagad, cash-on-delivery, and card payment options
- **Order confirmation** — post-checkout success receipt
- **Search suggestions** — AJAX-powered live search with local database fallback

### WooCommerce Integration Engine
- **Live product sync** — creates, updates, and archives products with automatic cache invalidation
- **Order sync** — pulls processing and completed orders with line items
- **Stock sync** — lightweight sync of stock quantities, prices, and SKUs
- **Price history tracking** — every price change is logged for audit and analytics
- **API request logging** — latency, status codes, and errors recorded per request
- **Dead-letter retry queue** — failed syncs are queued for later retry with email notifications on critical failures

### Integration Dashboard (`/woocommerce/*`)
- **Dashboard** — KPIs for product counts, order stats, stock totals, API response times, and unresolved failures
- **Products** — synced product management with search, status filters, and price history
- **Orders** — synced order management with status filters
- **Logs** — full API audit log with success/failure filtering
- **Manual sync trigger** — trigger product, order, stock, or full sync on demand
- **Retry failures** — reprocess unresolved sync failures from the dead-letter queue

### Other
- **Auth** — Laravel auth with Google OAuth via a unified auth controller
- **Customer accounts** — `/my-account` dashboard with order tracking and profile management
- **Admin analytics** — `/admin/analytics` dashboard with API metrics
- **B2B stocklot** — legacy stocklot and quotation routes (from earlier B2B phase)

---

## Technology Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 10 (`laravel/framework: ^10.0`) |
| **PHP** | 8.1+ / 8.3 |
| **API Client** | `Illuminate\Support\Facades\Http` (Guzzle 7) |
| **Database** | MySQL |
| **Styling** | Custom CSS design system + Bootstrap 5 + FontAwesome 6 |
| **Views** | Blade templates |

---

## Environment Variables

Copy `.env.example` to `.env` and configure:

```ini
# WooCommerce REST API
WOO_URL=https://deencommerce.com
WOO_CONSUMER_KEY=ck_...
WOO_CONSUMER_SECRET=cs_...
WOO_SYNC_INTERVAL=5
WOO_PER_PAGE=100
WOO_TIMEOUT=30
WOO_CONNECT_TIMEOUT=10
WOO_CACHE_TTL=60

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deen_commerce
DB_USERNAME=root
DB_PASSWORD=

# App
APP_KEY=base64:...
```

---

## Installation

### Prerequisites
- PHP >= 8.1 (8.3 recommended)
- Composer
- MySQL
- Node.js & NPM

### Setup

```bash
# 1. Clone the repository
git clone <repo-url>
cd B2B-StockLot-E-Commerce-BD

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup
#    Create a MySQL database, update .env, then:
php artisan migrate

# 5. Build frontend assets
npm run dev

# 6. Start the dev server
php artisan serve
```

Visit `http://localhost:8000`.

---

## Docker Setup

For a containerized local development environment, use Docker Compose.

### Prerequisites
- Docker & Docker Compose

### Quick Start

```bash
# 1. Clone and enter the project
git clone <repo-url>
cd B2B-StockLot-E-Commerce-BD

# 2. Copy environment file
cp .env.example .env

# 3. Start all containers
 docker compose up -d

# 4. Install dependencies inside the app container
docker compose exec app composer install
docker compose exec node npm install

# 5. Generate app key
docker compose exec app php artisan key:generate

# 6. Run migrations
docker compose exec app php artisan migrate

# 7. Build frontend assets
docker compose exec node npm run dev
```

### Services

| Service | Container | Port | Description |
|---|---|---|---|
| **Nginx** | `deen-commerce-nginx` | `8080` | Web server (visit `http://localhost:8080`) |
| **PHP** | `deen-commerce-app` | `9000` | Laravel application |
| **MySQL** | `deen-commerce-mysql` | `3306` | Database |
| **Node** | `deen-commerce-node` | — | Frontend asset compilation |

### Common Commands

```bash
# Stop all containers
docker compose down

# Stop and remove volumes (fresh start)
docker compose down -v

# View logs
docker compose logs -f app
docker compose logs -f nginx

# Run artisan commands
docker compose exec app php artisan <command>

# Run tests
docker compose exec app ./vendor/bin/phpunit

# Access MySQL
docker compose exec mysql mysql -u root -p deen_commerce
```

---

## Project Structure

```
├── app/
│   ├── Console/Commands/
│   │   └── SyncWooCommerceCommand.php      # artisan sync:woocommerce
│   ├── Enums/
│   │   └── WooSyncStatus.php               # Synced, Pending, Archived
│   ├── Exceptions/
│   │   └── WooCommerceException.php        # Custom API exception
│   ├── Http/Controllers/
│   │   ├── DeenCommerceStoreController.php # Retail storefront
│   │   ├── WooCommerceDashboardController.php # Integration dashboard
│   │   └── CategoryController.php
│   ├── Jobs/
│   │   └── ProcessWooSyncFailure.php       # Dead-letter queue retry
│   ├── Models/
│   │   ├── WooProduct.php                  # Products (soft-deletes)
│   │   ├── WooOrder.php                    # Orders
│   │   ├── WooOrderItem.php                # Order line items
│   │   ├── WooPriceHistory.php             # Price change tracking
│   │   ├── WooApiLog.php                   # API request logs
│   │   └── WooSyncFailure.php              # Failed sync records
│   ├── Notifications/
│   │   └── WooCommerceSyncFailed.php       # Critical failure email alert
│   └── Services/
│       ├── WooCommerceService.php          # REST API client with retries
│       └── WooCommerceSyncService.php      # Data sync engine
├── docker/
│   └── nginx/
│       └── default.conf                     # Nginx site configuration
├── Dockerfile                                # PHP 8.3 app container
├── docker-compose.yml                        # Local dev environment
├── .dockerignore                             # Build exclusions
├── config/
│   └── woocommerce.php                       # WooCommerce config options
├── database/migrations/
│   └── 2026_08_14_000001..000006           # WooCommerce tables
├── public/css/
│   ├── deen-commerce-store.css             # Storefront styles
│   └── woocommerce-dashboard.css           # Dashboard styles
├── resources/views/
│   ├── store/                              # Storefront Blade templates
│   └── woocommerce/                        # Dashboard Blade templates
├── routes/web.php
└── tests/
    ├── Unit/WooCommerceServiceTest.php
    └── Feature/SyncWooCommerceCommandTest.php
```

---

## Key Routes

| Route | Method | Description |
|---|---|---|
| `/` | GET | Storefront homepage |
| `/categories` | GET | Category directory |
| `/category/{id}` | GET | Category products |
| `/product/{id}` | GET | Product detail page |
| `/checkout` | GET/POST | Checkout form & processing |
| `/order-success/{id}` | GET | Order confirmation |
| `/woocommerce/dashboard` | GET | Integration dashboard |
| `/woocommerce/products` | GET | Synced products management |
| `/woocommerce/orders` | GET | Synced orders management |
| `/woocommerce/logs` | GET | API audit logs |
| `/woocommerce/sync` | POST | Trigger manual sync |
| `/woocommerce/retry-failures` | POST | Retry dead-letter queue |

---

## Running Tests

```bash
./vendor/bin/phpunit
```

---

## Data Flow

```
WooCommerce API (deencommerce.com)
        │
        ▼
WooCommerceService          ← HTTP client, retry, logging
        │
        ▼
WooCommerceSyncService      ← upsert products, orders, stock
        │
        ▼
Local MySQL DB              ← woo_products, woo_orders, woo_api_logs, etc.
        │
        ▼
Storefront Controller       ← live API first, local DB fallback
        │
        ▼
Blade Views                 ← rendered storefront UI
```

The sync runs every 5 minutes via `php artisan sync:woocommerce`. The storefront always tries the live WooCommerce API first and falls back to local data if the API is unreachable.

---

## License

MIT License — see [LICENSE](LICENSE) for details.
