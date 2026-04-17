<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Product Routes
    Route::resource('products', ProductController::class);
    
    // Export Routes
    Route::get('/products/{product}/export/{format}', [ProductController::class, 'export'])->name('products.export');
    Route::get('/products-export/{format}', [ProductController::class, 'exportAll'])->name('products.export-all');
    
    // Image upload routes
    Route::post('/products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::post('/products/{product}/images/reorder', [ProductImageController::class, 'reorder'])->name('products.images.reorder');
});

// Admin Routes - Protected by EnsureUserIsAdmin Middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
});

require __DIR__.'/auth.php';
