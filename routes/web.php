<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DeenCommerceStoreController;

Route::get('/', [DeenCommerceStoreController::class, 'index'])->name('store.index');
Route::get('/store/search/suggestions', [DeenCommerceStoreController::class, 'searchSuggestions'])->name('store.search.suggestions');
Route::get('/store/product/{id}', [DeenCommerceStoreController::class, 'showProduct'])->name('store.product.show');
Route::get('/categories', [DeenCommerceStoreController::class, 'categories'])->name('store.categories');
Route::get('/category/{id}', [DeenCommerceStoreController::class, 'categoryProducts'])->name('store.category');
Route::get('/product/{id}', [DeenCommerceStoreController::class, 'productDetail'])->name('store.product.detail');
Route::get('/checkout', [DeenCommerceStoreController::class, 'checkout'])->name('store.checkout');
Route::post('/checkout', [DeenCommerceStoreController::class, 'processCheckout'])->name('store.checkout.process');
Route::get('/order-success/{id}', [DeenCommerceStoreController::class, 'orderSuccess'])->name('store.order.success');



// Unified Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'showAuthForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'login']);
Route::get('register', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'showAuthForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Google & Facebook OAuth 2.0 Integration Routes
Route::get('auth/google', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('auth/facebook', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');


// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes...
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

// Dashboard Routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/seller/dashboard', [HomeController::class, 'sellerDashboard'])->name('seller.dashboard');
Route::get('/buyer/dashboard', [HomeController::class, 'buyerDashboard'])->name('buyer.dashboard');

// Product Routes
Route::resource('products', ProductController::class);
Route::get('/products/{product}/bulk-pricing', [ProductController::class, 'getBulkPricing'])->name('products.bulk-pricing');

// Order Routes
Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// Quotation Routes
Route::resource('quotations', QuotationController::class)->except(['edit', 'update', 'destroy']);
Route::post('/orders/{order}/quotations', [QuotationController::class, 'store'])->name('orders.quotations.store');
Route::patch('/quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
Route::patch('/quotations/{quotation}/reject', [QuotationController::class, 'reject'])->name('quotations.reject');

// Category Routes
Route::get('/categories', [DeenCommerceStoreController::class, 'categories'])->name('store.categories');
Route::get('/categories/{id}', [DeenCommerceStoreController::class, 'categoryProducts'])->name('store.category');



// Customer Account & Order Tracking Routes
Route::prefix('my-account')->name('account.')->group(function () {
    Route::get('/', [App\Http\Controllers\CustomerAccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [App\Http\Controllers\CustomerAccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\CustomerAccountController::class, 'trackOrder'])->name('orders.track');
    Route::post('/profile', [App\Http\Controllers\CustomerAccountController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [App\Http\Controllers\CustomerAccountController::class, 'updatePassword'])->name('password.update');
});

// Profile Routes
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');


// Admin Routes
Route::redirect('/admin', '/admin/analytics')->name('admin.dashboard');
Route::get('/admin/analytics', [App\Http\Controllers\AdminAnalyticsController::class, 'index'])->name('admin.analytics');
Route::get('/admin/analytics/export', [App\Http\Controllers\AdminAnalyticsController::class, 'export'])->name('admin.analytics.export');
Route::get('/admin/api/metrics', [App\Http\Controllers\AdminAnalyticsController::class, 'apiMetrics'])->name('admin.api.metrics');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/users', [HomeController::class, 'myUsers'])->name('admin.users');
});


// API Routes for AJAX calls
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/products/{product}/bulk-pricing', [ProductController::class, 'getBulkPricing']);
});

// Stocklot specific routes
Route::get('/stocklots', [ProductController::class, 'index'])->defaults('stocklot', true)->name('stocklots.index');

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// WooCommerce Integration Routes
use App\Http\Controllers\WooCommerceDashboardController;

Route::prefix('woocommerce')->name('woocommerce.')->group(function () {
    Route::get('/dashboard', [WooCommerceDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [WooCommerceDashboardController::class, 'products'])->name('products');
    Route::get('/orders', [WooCommerceDashboardController::class, 'orders'])->name('orders');
    Route::get('/logs', [WooCommerceDashboardController::class, 'logs'])->name('logs');
    Route::post('/sync', [WooCommerceDashboardController::class, 'triggerSync'])->name('sync');
    Route::post('/retry-failures', [WooCommerceDashboardController::class, 'retryFailures'])->name('retry-failures');
});

