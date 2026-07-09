<?php

use Illuminate\Support\Facades\Route;
use Plugins\ProductCatalog\Controllers\PublicShopController;

Route::middleware('web')->group(function () {
    Route::get('shop', [PublicShopController::class, 'index'])->name('shop.index');
    Route::get('shop/product/{slug}', [PublicShopController::class, 'show'])->name('shop.show');
});
