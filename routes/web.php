<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'home']);
Route::get('/product-details/{id}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/why', [HomeController::class, 'why'])->name('why');
Route::get('/testimonial', [HomeController::class, 'testimonial'])->name('testimonial');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'customer'])->controller(HomeController::class)->group(function () {
    Route::get('/dashboard', 'loginHome')->name('dashboard');
    Route::get('/my-orders', 'myOrders')->name('orders.index');
    Route::get('/add-cart/{id}', 'addCart')->name('cart.add');
    Route::get('/my-cart', 'myCart')->name('cart.index');
    Route::get('/delete-cart/{id}', 'deleteCart')->name('cart.delete');
    Route::post('/confirm-order', 'confirmOrder')->name('order.confirm');
});

/*
|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', 'edit')->name('edit');
    Route::patch('/', 'update')->name('update');
    Route::delete('/', 'destroy')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Category
    Route::get('/categories', [AdminController::class, 'viewCategory'])->name('category.index');
    Route::post('/categories', [AdminController::class, 'addCategory'])->name('category.store');
    Route::get('/categories/{id}/delete', [AdminController::class, 'deleteCategory'])->name('category.delete');
    Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('category.edit');
    Route::post('/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('category.update');

    // Product
    Route::get('/products/create', [AdminController::class, 'addProduct'])->name('product.create');
    Route::post('/products', [AdminController::class, 'uploadProduct'])->name('product.store');
    Route::get('/products', [AdminController::class, 'viewProduct'])->name('product.index');
    Route::get('/products/{id}/delete', [AdminController::class, 'deleteProduct'])->name('product.delete');
    Route::get('/products/{id}/edit', [AdminController::class, 'updateProduct'])->name('product.edit');
    Route::post('/products/{id}/update', [AdminController::class, 'editProduct'])->name('product.update');
    Route::get('/products/search', [AdminController::class, 'productSearch'])->name('product.search');

    // Orders
    Route::get('/orders', [AdminController::class, 'viewOrder'])->name('order.index');
    Route::get('/orders/{id}/on-the-way', [AdminController::class, 'onTheWay'])->name('order.onTheWay');
    Route::get('/orders/{id}/delivered', [AdminController::class, 'delivered'])->name('order.delivered');
    Route::get('/orders/{id}/print', [AdminController::class, 'printPdf'])->name('order.printPdf');
});

/*
|--------------------------------------------------------------------------
| Stripe Payment Routes
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)->group(function () {
    Route::get('/stripe/{value}', 'stripe')->name('stripe.form');
    Route::post('/stripe/{value}', 'stripePost')->name('stripe.post');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
