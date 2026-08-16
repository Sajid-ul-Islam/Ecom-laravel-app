# DEEN.im — Retail Fashion & Denim E-Commerce Platform

A high-performance retail denim & urban apparel e-commerce platform built on **Laravel 10** (PHP 8.3) and integrated live with the **WooCommerce REST API** (`wc/v3`). **DEEN.im** syncs products, orders, inventory, and price history in real-time, serving an ultra-fast, luxury customer storefront with graceful fallback caching when the remote API is unreachable.

**Live Store:** [deencommerce.com](https://deencommerce.com)

---

## 🌟 Key Features

### 1. Modern Storefront & Design System
- **`DEEN.im` Brand Identity** — High-contrast typography lockup with an active glowing amber domain badge (`DEEN.im`).
- **8-Theme Architectural Engine** (persisted per visitor, hot-swappable from the nav):
  - 👖 **13.5oz Washed Denim** *(Default authentic indigo twill)*
  - 🧵 **Raw Denim Fabric** *(Deep indigo twill-weave texture with copper rivet accents)*
  - 🌙 **Midnight Studio Dark** *(Luxury obsidian & neon violet)*
  - ✨ **Crystal Glassmorphism** *(Translucent frosted glass, specular borders & cyan glow)*
  - ⚡ **Cyberpunk Urban Neon** *(Charcoal & electric pink)*
  - 🌿 **Botanical Sage** *(Green-tinted calm canvas)*
  - 🌊 **Royal Azure** *(Blue-tinted ocean canvas)*
  - ☀️ **Studio Minimal Light** *(Clean slate & ocean navy)*
- **WCAG AA Accessibility Compliance** — All themes audited for 4.5:1 text contrast, 3:1 focus-ring contrast, accessible placeholders, and dark-mode pill/pastel token overrides.
- **Icon-Centric Header & Navigation**:
  - Instant live predictive search drawer.
  - Theme selector palette.
  - Interactive VIP loyalty coins pill (`450 COINS`).
  - Saved wishlist button with live item counter (`#navWishlistCount`).
  - Unified shopping cart drawer with live badge (`#cartCount`).
  - One-tap account profile & services dropdown.
- **Thumb-Zone Mobile Bottom Navigation**:
  - Streamlined 5-button dock: **Shop** (`storefront`), **Categories** (`grid_view`), **Search** (`search`), **Cart** (`shopping_cart`), and **Account** (`person`).
  - Integrated **Account Drawer** (`#mobileAccountModal`) providing instant access to Live Order Tracking, Wishlist, VIP Rewards Vault, Profile Settings, and 1-Tap Login.
- **Clean Product Presentation**:
  - Unobstructed high-fashion product photos with fabric wash swatches and motion video previews.
  - Minimalist glowing stock dot indicators (`.deen-stock-dot`) embedded within the card details.
  - Interactive thumbnail gallery with active-state ring and hover transitions.
- **Global Flash Sale Timer** — Live countdown badge (`ENDS IN 05h : 42m : 18s`) with auto-pausing hero carousel on hover.
- **UI Polish & Micro-Interactions**:
  - Category cards with hover lift + shadow; whole card is a link.
  - Skeleton loader pulse placeholders while product data streams in.
  - Badges auto-hide at zero counts; page-level fade-in transition.
- **PWA (Progressive Web App)** — Installable via `manifest.json` (standalone display, Deen branding) with an offline-first service worker (`sw.js`) that caches the shell, stylesheet, and CDN assets.

### 2. Client Account & 5-Stage Live Order Tracker (`/my-account`)
- **Interactive Courier Tracking**: 5-stage real-time progress tracker (`Placed` ➔ `Inspected` ➔ `In Transit` ➔ `Out for Delivery` ➔ `Delivered`) with carrier details (Steadfast Courier / Pathao Express), dispatch hubs, tracking codes, and digital tax receipts.
- **Order History & Invoices**: Historical ledger of all client orders with status pills, carrier badges, itemized pricing in BDT (৳), and digital PDF invoice links.
- **Profile & Address Manager**: Manage personal contact details, verified email, phone (+880), and division/district shipping addresses.
- **VIP Rewards & Voucher Vault**: Loyalty coin ledger (450 coins) with 1-tap claimable discount coupons (`DEEN10`, `DEEN25`, `FREEDEL`).
- **Security & Credentials**: In-app password updates and session security management.

### 3. Unified Authentication Hub with 1-Tap OAuth
- **Social Sign-In**: 1-Tap **Google** and **Facebook** OAuth integration.
- **Luxury Sign In / Sign Up Modal (`#authModal`)**: Interactive tab switching with password visibility toggles, 256-bit SSL encryption trust badges, and 50 bonus loyalty coins on signup.
- **Dedicated Auth Portal (`/auth/login` & `/auth/register`)**: Responsive standalone auth views with automatic post-login redirection to the customer dashboard.

### 4. WooCommerce Integration Engine (`wc/v3`)
- **Live Product Sync**: Real-time upserting of products, variations, categories, and tags with automatic cache invalidation.
- **Order Synchronization**: Pulls processing and completed customer orders along with itemized line items.
- **Memory-Optimized Order Fetching**: Orders are requested with an explicit `_fields` whitelist so full payloads (huge `meta_data`/plugin blobs) never balloon PHP memory — critical for stores with thousands of orders.
- **Stock & Inventory Tracking**: Synchronizes stock levels, prices, and SKUs with exponential backoff retries (`4` attempts, 1s → 30s).
- **Historical Price Auditing**: Logs every price change into `woo_price_histories` for analytics.
- **Dead-Letter Retry Queue**: Captures failed synchronizations into `woo_sync_failures` for scheduled or manual reprocessing.
- **Observability**: Every request is logged to `woo_api_logs` (latency, status, endpoint); failures dispatch `ProcessWooSyncFailure` onto the `woo-dead-letter` queue.

### 5. Integration Hub & Admin Analytics (`/woocommerce/*` & `/admin/analytics`)
- **Integration Hub**: Real-time sync statistics, request latency metrics, error logs, and on-demand synchronization triggers.
- **Admin Analytics Dashboard**: High-level store metrics, sales revenue summaries, conversion charts, and API health status.

### 6. Mobile UX Enhancement Suite
- **Wishlist** — Save-to-wishlist buttons with live `#navWishlistCount` badge, persisted per visitor.
- **Quick View** — Product peek modal from category/card grids without leaving the page.
- **Product Variants** — Fabric wash swatch & variant selection with price updates.
- **Order Tracking Components** — Thumb-zone dock with one-tap access to live order tracking from the mobile account drawer.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 10 (`laravel/framework: ^10.0`) |
| **PHP Version** | PHP 8.1+ / 8.3 (tested on PHP 8.3.6) |
| **HTTP Client** | `Illuminate\Support\Facades\Http` (Guzzle 7) |
| **Database** | MySQL 8.0 / SQLite |
| **Cache / Session / Queue** | Redis 7 (phpredis) |
| **Frontend Styling** | Vanilla CSS Design System (`public/css/deen-commerce-store.css`) |
| **UI Components** | Blade Templates + Bootstrap 5 + Material Symbols + FontAwesome 6 |
| **Social Auth** | Laravel Socialite (Google & Facebook OAuth) |
| **PWA** | Manifest + service worker (`public/manifest.json`, `public/sw.js`) |
| **Containerization** | Docker Compose (PHP 8.3-FPM, Nginx, MySQL 8.0, Redis 7, Node 20) |
| **CI** | GitHub Actions — PHPUnit on push/PR (`tests.yml`) |

---

## 📋 Environment Configuration

Create or update your `.env` file with the following variables:

```ini
APP_NAME="DEEN.im"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deen_commerce
DB_USERNAME=root
DB_PASSWORD=

# WooCommerce REST API Credentials
WOO_URL=https://deencommerce.com
WOO_CONSUMER_KEY=ck_...
WOO_CONSUMER_SECRET=cs_...
WOO_SYNC_INTERVAL=5
WOO_PER_PAGE=100
WOO_TIMEOUT=30
WOO_CONNECT_TIMEOUT=10
WOO_CACHE_TTL=60

# Social OAuth (Google & Facebook)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"
```

---

## 🚀 Installation & Local Development

### Option A — Docker Compose (recommended)

The stack runs PHP 8.3-FPM, Nginx, MySQL 8.0, Redis 7, a Redis queue worker, and a Node watcher. A convenience wrapper `./dc.sh` (plus a thin `Makefile`) handles everything:

```bash
# 1. Clone the repository
git clone https://github.com/Sajid-ul-Islam/Ecom-laravel-app.git
cd Ecom-laravel-app

# 2. Configure environment
cp .env.example .env
# (set your DB/WooCommerce credentials — defaults work for the stack)

# 3. Build & start the stack (installs deps, runs migrations, starts php-fpm)
./dc.sh up

# 4. Open the storefront on port 8080
#    http://localhost:8080
```

Useful `dc.sh` commands:

```bash
./dc.sh logs            # follow app logs
./dc.sh shell           # bash into the app container
./dc.sh artisan route:list
./dc.sh composer require foo/bar
./dc.sh npm run production
./dc.sh migrate         # run pending migrations
./dc.sh test            # run PHPUnit in the container
./dc.sh sync            # trigger a WooCommerce sync
./dc.sh down            # stop (keeps data)
./dc.sh destroy         # stop AND delete all volumes (mysql, redis, vendor)
```

The PHP container ships with a raised `memory_limit = 512M` (`docker/php/zz-memory.ini`) so large WooCommerce order syncs don't OOM.

### Option B — Local (bare-metal)

### Prerequisites
- PHP >= 8.1 / 8.3
- Composer
- MySQL or SQLite
- Node.js & NPM

### Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/Sajid-ul-Islam/Ecom-laravel-app.git
cd Ecom-laravel-app

# 2. Install PHP & Node dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Run database migrations
php artisan migrate

# 5. Build frontend assets & clear caches
php artisan view:clear
php artisan view:cache
npm run dev

# 6. Start the local server
php artisan serve
```

Visit **`http://localhost:8000`** in your browser.

---

## 🔄 WooCommerce Synchronization Commands

You can run synchronization manually via Artisan:

```bash
# Full sync (Products + Orders + Stock)
php artisan sync:woocommerce --type=all

# Sync products only
php artisan sync:woocommerce --type=products

# Sync orders only
php artisan sync:woocommerce --type=orders

# Sync stock quantities & prices only
php artisan sync:woocommerce --type=stock

# Retry failed items in the dead-letter queue
php artisan sync:woocommerce --retry-failed
```

---

## 🗺️ Key Web Routes & Endpoints

| Route Name | Method | Path | Controller Action | Description |
|---|---|---|---|---|
| `store.index` | `GET` | `/` | `DeenCommerceStoreController@index` | Retail Storefront Homepage |
| `store.search.suggestions` | `GET` | `/store/search/suggestions` | `DeenCommerceStoreController@searchSuggestions` | Live Predictive Search |
| `store.categories` | `GET` | `/categories` | `DeenCommerceStoreController@categories` | Categories Directory Showcase |
| `store.category` | `GET` | `/category/{id}` | `DeenCommerceStoreController@categoryProducts` | Category Products & Sorting |
| `store.product.detail` | `GET` | `/product/{id}` | `DeenCommerceStoreController@productDetail` | Full Product Landing Page |
| `store.checkout` | `GET` | `/checkout` | `DeenCommerceStoreController@checkout` | Retail Checkout Page |
| `store.checkout.process` | `POST` | `/checkout` | `DeenCommerceStoreController@processCheckout` | Process Order & Payment |
| `store.order.success` | `GET` | `/order-success/{id}` | `DeenCommerceStoreController@orderSuccess` | Order Confirmation Receipt |
| `account.dashboard` | `GET` | `/my-account` | `CustomerAccountController@dashboard` | Customer Profile & Services Hub |
| `account.orders` | `GET` | `/my-account/orders` | `CustomerAccountController@orders` | 5-Stage Live Order Tracking |
| `account.orders.track` | `GET` | `/my-account/orders/{id}` | `CustomerAccountController@trackOrder` | Single Order Tracking View |
| `auth.google` | `GET` | `/auth/google` | `UnifiedAuthController@redirectToGoogle` | Google 1-Tap OAuth Redirect |
| `auth.facebook` | `GET` | `/auth/facebook` | `UnifiedAuthController@redirectToFacebook`| Facebook 1-Tap OAuth Redirect |
| `woocommerce.dashboard`| `GET` | `/woocommerce/dashboard` | `WooCommerceDashboardController@dashboard` | Integration Hub Live Dashboard |
| `woocommerce.products` | `GET` | `/woocommerce/products` | `WooCommerceDashboardController@products` | Synced Products Management |
| `woocommerce.orders` | `GET` | `/woocommerce/orders` | `WooCommerceDashboardController@orders` | Synced Orders Management |
| `woocommerce.logs` | `GET` | `/woocommerce/logs` | `WooCommerceDashboardController@logs` | REST API Audit Logs |
| `admin.analytics` | `GET` | `/admin/analytics` | `AdminAnalyticsController@index` | Executive Store Analytics |
| `admin.analytics.export` | `GET` | `/admin/analytics/export` | `AdminAnalyticsController@export` | BI CSV Export |

---

## 🧪 Running the Test Suite

Run the full automated PHPUnit test suite:

```bash
./vendor/bin/phpunit
```

To run feature authentication tests specifically:

```bash
./vendor/bin/phpunit tests/Feature/UnifiedAuthTest.php
```

---

## 📄 License

This software is released under the **MIT License**.
