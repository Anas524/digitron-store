<?php

use App\Http\Controllers\Admin\AdminOrderController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShopStateController;
use App\Http\Controllers\WishlistController;

use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\HomeShowcaseController;
use App\Http\Controllers\AdminCategoryController;

Route::get('/__ping', fn() => 'LARAVEL OK');

// =======================
// STORE (PUBLIC)
// =======================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop/{category?}', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// =======================
// CUSTOMER AUTH
// =======================
Route::middleware('guest')->group(function () {
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register');

    Route::get('/login', function () {
        return redirect()->route('home')
            ->with('openAccountPanel', 1)
            ->with('accountTab', 'login');
    })->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});

// =======================
// ADMIN
// =======================
Route::prefix('admin')->name('admin.')->group(function () {

    // block direct /admin/login
    Route::get('/login', fn() => redirect()->route('home'))->name('login');

    // login submit (navbar dropdown)
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.post');

    // logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('admin')
        ->name('logout');

    // protect all admin pages
    Route::middleware(['admin'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [DashboardController::class, 'search'])->name('search');

        // Products CRUD
        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
        Route::put('products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Home Showcase
        Route::get('/home-showcase', [HomeShowcaseController::class, 'index'])->name('home-showcase.index');
        Route::post('/home-showcase', [HomeShowcaseController::class, 'store'])->name('home-showcase.store');
        Route::put('/home-showcase/{slide}', [HomeShowcaseController::class, 'update'])->name('home-showcase.update');
        Route::delete('/home-showcase/{slide}', [HomeShowcaseController::class, 'destroy'])->name('home-showcase.destroy');

        // Images
        Route::post('product-images/{image}/primary', [AdminProductController::class, 'setPrimary'])
            ->name('product_images.primary');
        Route::delete('product-images/{image}', [AdminProductController::class, 'deleteImage'])
            ->name('product_images.delete');

        Route::get('/newsletter', [NewsletterController::class, 'adminIndex'])->name('newsletter.index');
        Route::get('/quotes', [QuoteController::class, 'adminIndex'])->name('quotes.index');
        Route::delete('/quotes/{id}', [QuoteController::class, 'adminDestroy'])->name('quotes.destroy');
        Route::get('/quotes/{id}/attachments', [QuoteController::class, 'adminAttachments'])->name('quotes.attachments');
        Route::post('/quotes/{id}/mark-seen', [QuoteController::class, 'markSeen'])->name('quotes.markSeen');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::get('/quote', [QuoteController::class, 'index'])->name('quote');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');

Route::get('/shop/state', [ShopStateController::class, 'state'])->name('shop.state');


Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
    Route::delete('/wishlist', [WishlistController::class, 'clear'])->name('wishlist.clear');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/checkout/complete/{order}', [CartController::class, 'complete'])->name('checkout.complete');
    Route::get('/my-orders', [CartController::class, 'myOrders'])->name('my.orders');
});

// Route::get('/login', function () {
//     return redirect()->route('home')->with('openAccountDropdown', 1);
// })->name('login');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::view('/about', 'pages.about')->name('about');
