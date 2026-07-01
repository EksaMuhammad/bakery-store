<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Customer Front-End Routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/catalog', [ProductController::class, 'catalog'])->name('catalog');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('detail');

// Shopping Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Tracking Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/track', [CheckoutController::class, 'track'])->name('track');
Route::get('/order/invoice/{order_code}', [CheckoutController::class, 'invoice'])->name('invoice');

// Admin Panel Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Admin Product Management (CRUD)
Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
Route::get('/admin/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
Route::put('/admin/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

// Admin Order Management
Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
Route::get('/admin/orders/{id}', [AdminController::class, 'orderDetail'])->name('admin.orders.show');
Route::put('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
