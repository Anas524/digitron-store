<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\ShopController;
use App\Http\Controllers\Store\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
