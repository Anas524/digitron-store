<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProductController;

Route::get('/__ping', fn() => 'LARAVEL OK');

// =======================
// STORE (PUBLIC)
// =======================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop/{category?}', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// =======================
// ADMIN
// =======================
Route::prefix('admin')->name('admin.')->group(function () {

    // If someone opens /admin/login manually -> send them to home
    Route::get('/login', fn() => redirect()->route('home'))->name('login');

    // Keep login submit for navbar dropdown
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('guest')
        ->name('login.post');

    // admin logout (protect with your admin middleware, not auth)
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('admin')
        ->name('logout');

    // protect all admin pages with AdminOnly
    Route::middleware(['admin'])->group(function () {
        Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

        Route::resource('products', AdminProductController::class);
        Route::post('product-images/{image}/primary', [AdminProductController::class, 'setPrimary'])->name('product_images.primary');
        Route::delete('product-images/{image}', [AdminProductController::class, 'deleteImage'])->name('product_images.delete');

        Route::get('newsletter', fn() => view('admin.newsletter.index'))->name('newsletter.index');
        Route::get('quotes', fn() => view('admin.quotes.index'))->name('quotes.index');
    });
});
