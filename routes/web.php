<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\ArtisanDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\TelegramAuthController;
use App\Http\Controllers\Auth\TelegramWebAppAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

// Debug route
Route::get('/debug-db', function () {
    return [
        'base_path' => base_path(),
        'database_path' => database_path(),
        'db_database' => config('database.connections.sqlite.database'),
        'env_db_database' => env('DB_DATABASE'),
        'dir' => __DIR__
    ];
});

// Guest routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/login/telegram', [TelegramAuthController::class, 'redirect'])->name('login.telegram');
    Route::get('/login/telegram/{token}/status', [TelegramAuthController::class, 'status'])->name('login.telegram.status');
    Route::post('/login/telegram/{token}/complete', [TelegramAuthController::class, 'complete'])->name('login.telegram.complete');
});

// Telegram bot webhook (no CSRF, no auth)
Route::post('/telegram/webhook', TelegramWebhookController::class)->name('telegram.webhook');

// Telegram Mini App auto-login (verifies Telegram WebApp initData signature)
Route::post('/telegram/webapp-auth', [TelegramWebAppAuthController::class, 'login'])->name('telegram.webapp-auth');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/checkout/apply-promo', [OrderController::class, 'applyPromo'])->name('checkout.apply-promo');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/start/{user}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    // User Dashboard
    Route::prefix('dashboard')->name('user.')->group(function () {
        Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/reviews', [UserDashboardController::class, 'reviews'])->name('reviews');
        Route::post('/become-artisan', [UserDashboardController::class, 'becomeArtisan'])->name('become-artisan');
    });
});

// Artisan routes
Route::middleware(['auth', 'role:artisan'])->prefix('artisan')->name('artisan.')->group(function () {
    Route::get('/dashboard', [ArtisanDashboardController::class, 'dashboard'])->name('dashboard');

    // Profile (always accessible, even before approval)
    Route::get('/profile', [ArtisanDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ArtisanDashboardController::class, 'updateProfile'])->name('profile.update');

    Route::middleware('artisan.approved')->group(function () {
        // Products
        Route::get('/products', [ArtisanDashboardController::class, 'products'])->name('products');
        Route::get('/products/create', [ArtisanDashboardController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [ArtisanDashboardController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [ArtisanDashboardController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [ArtisanDashboardController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [ArtisanDashboardController::class, 'deleteProduct'])->name('products.delete');

        // Orders
        Route::get('/orders', [ArtisanDashboardController::class, 'orders'])->name('orders');
        Route::patch('/orders/{orderItem}/status', [ArtisanDashboardController::class, 'updateOrderStatus'])->name('orders.status');

        // Discounts
        Route::get('/discounts', [ArtisanDashboardController::class, 'discounts'])->name('discounts');
        Route::get('/discounts/create', [ArtisanDashboardController::class, 'createDiscount'])->name('discounts.create');
        Route::post('/discounts', [ArtisanDashboardController::class, 'storeDiscount'])->name('discounts.store');
        Route::patch('/discounts/{discount}/toggle', [ArtisanDashboardController::class, 'toggleDiscount'])->name('discounts.toggle');

        // Analytics
        Route::get('/analytics', [ArtisanDashboardController::class, 'analytics'])->name('analytics');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

    // Artisans
    Route::get('/artisans', [AdminController::class, 'artisans'])->name('artisans');
    Route::patch('/artisans/{artisan}/approve', [AdminController::class, 'approveArtisan'])->name('artisans.approve');
    Route::patch('/artisans/{artisan}/reject', [AdminController::class, 'rejectArtisan'])->name('artisans.reject');

    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::patch('/products/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('products.toggle');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});
