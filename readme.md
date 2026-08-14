# DEEN.im — Retail Fashion & Denim E-Commerce Platform

A high-performance retail denim & urban apparel e-commerce platform built on **Laravel 10** (PHP 8.3) and integrated live with the **WooCommerce REST API** (`wc/v3`). **DEEN.im** syncs products, orders, inventory, and price history in real-time, serving an ultra-fast, luxury customer storefront with graceful fallback caching when the remote API is unreachable.

**Live Store:** [deencommerce.com](https://deencommerce.com)

---

## 🌟 Key Features

### 1. Modern Storefront & Design System
- **`DEEN.im` Brand Identity** — High-contrast typography lockup with an active glowing amber domain badge (`DEEN.im`).
- **5-Theme Architectural Engine**:
  - 👖 **13.5oz Washed Denim** *(Default authentic indigo twill)*
  - 🌙 **Midnight Studio Dark** *(Luxury obsidian & neon violet)*
  - ✨ **Crystal Glassmorphism** *(Translucent frosted glass, specular borders & cyan glow)*
  - ⚡ **Cyberpunk Urban Neon** *(Charcoal & electric pink)*
  - ☀️ **Studio Minimal Light** *(Clean slate & ocean navy)*
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
- **Stock & Inventory Tracking**: Synchronizes stock levels, prices, and SKUs with exponential backoff retries.
- **Historical Price Auditing**: Logs every price change into `woo_price_histories` for analytics.
- **Dead-Letter Retry Queue**: Captures failed synchronizations into `woo_sync_failures` for scheduled or manual reprocessing.

### 5. Integration Hub & Admin Analytics (`/woocommerce/*` & `/admin/analytics`)
- **Integration Hub**: Real-time sync statistics, request latency metrics, error logs, and on-demand synchronization triggers.
- **Admin Analytics Dashboard**: High-level store metrics, sales revenue summaries, conversion charts, and API health status.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 10 (`laravel/framework: ^10.0`) |
| **PHP Version** | PHP 8.1+ / 8.3 (tested on PHP 8.3.6) |
| **HTTP Client** | `Illuminate\Support\Facades\Http` (Guzzle 7) |
| **Database** | MySQL / SQLite |
| **Frontend Styling** | Vanilla CSS Design System (`public/css/deen-commerce-store.css`) |
| **UI Components** | Blade Templates + Bootstrap 5 + Material Symbols + FontAwesome 6 |
| **Social Auth** | Laravel Socialite (Google & Facebook OAuth) |

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

### Prerequisites
- PHP >= 8.1 / 8.3
- Composer
- MySQL or SQLite
- Node.js & NPM

### Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/saajiidi/B2B-StockLot-E-Commerce-BD.git
cd B2B-StockLot-E-Commerce-BD

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
| `store.categories` | `GET` | `/categories` | `DeenCommerceStoreController@categories` | Categories Directory Showcase |
| `store.category` | `GET` | `/category/{id}` | `DeenCommerceStoreController@categoryProducts` | Category Products & Sorting |
| `store.product.detail` | `GET` | `/product/{id}` | `DeenCommerceStoreController@productDetail` | Full Product Landing Page |
| `store.checkout` | `GET` | `/checkout` | `DeenCommerceStoreController@checkout` | Retail Checkout Page |
| `store.checkout.process` | `POST` | `/checkout` | `DeenCommerceStoreController@processCheckout` | Process Order & Payment |
| `store.order.success` | `GET` | `/order-success/{id}` | `DeenCommerceStoreController@orderSuccess` | Order Confirmation Receipt |
| `account.dashboard` | `GET` | `/my-account` | `CustomerAccountController@dashboard` | Customer Profile & Services Hub |
| `account.orders` | `GET` | `/my-account/orders` | `CustomerAccountController@orders` | 5-Stage Live Order Tracking |
| `auth.unified.login` | `GET` | `/auth/login` | `UnifiedAuthController@showLoginForm` | Unified Login Portal |
| `auth.unified.register`| `GET` | `/auth/register` | `UnifiedAuthController@showRegisterForm`| VIP Club Registration Portal |
| `auth.google` | `GET` | `/auth/google` | `UnifiedAuthController@redirectToGoogle` | Google 1-Tap OAuth Redirect |
| `auth.facebook` | `GET` | `/auth/facebook` | `UnifiedAuthController@redirectToFacebook`| Facebook 1-Tap OAuth Redirect |
| `woocommerce.dashboard`| `GET` | `/woocommerce/dashboard` | `WooCommerceDashboardController@dashboard` | Integration Hub Live Dashboard |
| `woocommerce.products` | `GET` | `/woocommerce/products` | `WooCommerceDashboardController@products` | Synced Products Management |
| `woocommerce.orders` | `GET` | `/woocommerce/orders` | `WooCommerceDashboardController@orders` | Synced Orders Management |
| `woocommerce.logs` | `GET` | `/woocommerce/logs` | `WooCommerceDashboardController@logs` | REST API Audit Logs |
| `admin.analytics` | `GET` | `/admin/analytics` | `AdminAnalyticsController@index` | Executive Store Analytics |

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
