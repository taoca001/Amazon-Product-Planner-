<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductImageUploadController;
use App\Http\Controllers\Api\ProductKeywordController;

Route::middleware('api.token')->group(function () {
    // Bild-Upload (für n8n / Google Drive Automation)
    Route::post('/products/{product}/images/upload', [ProductImageUploadController::class, 'store'])
        ->name('api.products.images.upload');

    // Keyword-Analyse (für n8n / SE Ranking Automation)
    Route::get('/products', [ProductKeywordController::class, 'index'])
        ->name('api.products.index');

    Route::patch('/products/{product}/keywords', [ProductKeywordController::class, 'update'])
        ->name('api.products.keywords.update');
});
