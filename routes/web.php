<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserManagementController;
use App\Models\FeaturedSection;
use App\Models\Product;
Route::get('/', function () {
    $featuredSection = FeaturedSection::first();
    $featuredProducts = $featuredSection?->products() ?? Product::latest()->limit(3)->get();

    return view('home', compact('featuredSection', 'featuredProducts'));
});
Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/products', function () {
    return view('products');
});

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::put('/admin/featured-section', [DashboardController::class, 'updateFeatured'])->name('admin.featured.update');
});

Route::middleware('super_admin')->group(function () {
    Route::post('/admin/managers', [UserManagementController::class, 'storeManager'])->name('admin.managers.store');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/products', [ProductController::class, 'index'])->name('products');





Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout/confirm', [CheckoutController::class, 'processCheckout'])->name('checkout.confirm');
    Route::get('/orders/{order}/receipt', [CheckoutController::class, 'showReceipt'])->name('orders.receipt.show');
    Route::get('/orders/{order}/receipt/download', [CheckoutController::class, 'downloadReceipt'])->name('orders.receipt.download');
});
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
