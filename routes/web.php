<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TrendingController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================
// HOME
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/cart/count', [HomeController::class, 'getCartCount'])->name('cart.count');

// ========================
// CART (HANYA AUTH)
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
});

// ========================
// TRENDING
// ========================
Route::get('/trending', [TrendingController::class, 'trending'])->name('trending.index');
// ========================
// PRODUCT
// ========================
Route::prefix('produk')->name('produk.')->group(function () {
    Route::get('/', [ProdukController::class, 'index'])->name('all');
    Route::get('/kategori/{slug}', [ProdukController::class, 'kategori'])->name('kategori');
    Route::get('/{id}', [ProdukController::class, 'show'])->name('show');

    // ✅ FIX UNTUK NAVBAR SEARCH
    Route::get('/search', [ProdukController::class, 'search'])->name('search');

    Route::get('/trending', [HomeController::class, 'trending'])->name('trending');
    
    Route::post('/{id}/rate', [ProdukController::class, 'rate'])->name('rate');
});

// Promo Routes
Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
Route::get('/promo/{id}', [PromoController::class, 'detail'])->name('promo.detail');
// ========================
// SPORTS
// ========================
Route::prefix('sports')->name('sports.')->group(function () {
    Route::get('/running', [ProdukController::class, 'running'])->name('running');
    Route::get('/basketball', [ProdukController::class, 'basketball'])->name('basketball');
    Route::get('/football', [ProdukController::class, 'football'])->name('football');
    Route::get('/fitness', [ProdukController::class, 'fitness'])->name('fitness');
    Route::get('/outdoor', [ProdukController::class, 'outdoor'])->name('outdoor');
});

// ========================
// SEARCH
// ========================
Route::get('/search', [ProdukController::class, 'search'])->name('search');

// ========================
// CART DETAIL ROUTES (HANYA AUTH)
// ========================
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
    Route::get('/add/{id}', [CartController::class, 'addGet'])->name('add.get');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
});

// Wishlist routes dihapus karena fitur diganti menjadi rating bintang

// ========================
// AUTH
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// ========================
// LOGOUT
// ========================
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ========================
// ACCOUNT (AUTH)
// ========================
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/cancel', [AccountController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{id}/complete', [AccountController::class, 'completeOrder'])->name('orders.complete');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::get('/settings', [AccountController::class, 'settings'])->name('settings');
    Route::put('/settings', [AccountController::class, 'updateSettings'])->name('settings.update');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
    Route::get('/help', [AccountController::class, 'help'])->name('help');
});

Route::middleware('auth')->group(function () {
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});
/* =========================
| NOTIFICATIONS (FIX ERROR TERAKHIR)
========================= */
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.all');
Route::middleware('auth')->group(function () {
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
    Route::delete('/notifications/read-all', [NotificationController::class, 'deleteAllRead'])
        ->name('notifications.delete-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])
        ->name('notifications.recent');
});

// ========================
// STATIC PAGES
// ========================
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/shipping', 'pages.shipping')->name('shipping');
Route::view('/size-guide', 'pages.size-guide')->name('size-guide');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/sitemap', 'pages.sitemap')->name('sitemap');


// ========================
// ADMIN AREA
// ========================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
        Route::post('/orders/{id}/approve', [AdminController::class, 'approvePayment'])->name('orders.approve');
        Route::post('/orders/{id}/reject', [AdminController::class, 'rejectPayment'])->name('orders.reject');
        Route::get('/finance', [AdminController::class, 'finance'])->name('finance');
        Route::get('/finance/export/csv', [AdminController::class, 'financeExportCsv'])->name('finance.export.csv');
        Route::post('/finance/expenses', [AdminController::class, 'storeExpense'])->name('expenses.store');

        Route::get('/notifications/create', [AdminController::class, 'createNotification'])->name('notifications.create');
        Route::post('/notifications', [AdminController::class, 'storeNotification'])->name('notifications.store');
    });

// ========================
// CHECKOUT (AUTH)
// ========================
// Routes for checkout process
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/update', [CheckoutController::class, 'update'])->name('update');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/confirm/{orderId}', [CheckoutController::class, 'confirmForm'])->name('confirm');
    Route::post('/confirm/{orderId}', [CheckoutController::class, 'confirmSubmit'])->name('confirm.submit');
});
