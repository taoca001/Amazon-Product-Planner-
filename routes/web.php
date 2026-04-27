<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductDriveSyncController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // API Token Management (Profil)
    Route::get('/profile/tokens', [ApiTokenController::class, 'index'])->name('profile.tokens.index');
    Route::post('/profile/tokens', [ApiTokenController::class, 'store'])->name('profile.tokens.store');
    Route::delete('/profile/tokens/{token}', [ApiTokenController::class, 'destroy'])->name('profile.tokens.destroy');
    
    // Product Routes (edit uses show view)
    Route::resource('products', ProductController::class)->except(['edit']);
    
    // Export Routes (Format einschränken)
    Route::get('/products/{product}/export/{format}', [ProductController::class, 'export'])
        ->whereIn('format', ['csv', 'json'])
        ->name('products.export');
    Route::get('/products-export/{format}', [ProductController::class, 'exportAll'])
        ->whereIn('format', ['csv', 'json'])
        ->name('products.export-all');
    
    // Image upload routes
    Route::post('/products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::post('/products/{product}/images/reorder', [ProductImageController::class, 'reorder'])->name('products.images.reorder');

    // Google Drive Sync
    Route::post('/products/{product}/drive-sync', [ProductDriveSyncController::class, 'sync'])->name('products.drive-sync');

    // Operations (mit Rate Limiting: 10/Minute)
    Route::middleware('throttle:operations')->group(function () {
        Route::get('/operations', [OperationsController::class, 'index'])->name('operations.index');
        Route::post('/operations/keyword-analysis', [OperationsController::class, 'triggerKeywordAnalysis'])->name('operations.keyword-analysis');
        Route::post('/operations/keyword-metrics', [OperationsController::class, 'keywordMetrics'])->name('operations.keyword-metrics');
        Route::post('/operations/keywords-for-site', [OperationsController::class, 'keywordsForSite'])->name('operations.keywords-for-site');
        Route::post('/operations/keywords-for-keywords', [OperationsController::class, 'keywordsForKeywords'])->name('operations.keywords-for-keywords');
    });
});

// Admin Routes - Protected by EnsureUserIsAdmin + EnsureUserIsActive
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])
        ->name('users.toggle-active');
    Route::delete('/users/{user}/tokens/{token}', [AdminUserController::class, 'revokeToken'])
        ->name('users.tokens.revoke');
});

require __DIR__.'/auth.php';
