<?php

use Illuminate\Support\Facades\Route;
use Plugins\BotCommerce\Controllers\PublicShopController;
use Plugins\BotCommerce\Controllers\AdminProductController;

Route::middleware('web')->group(function () {
    // Public shop routes
    Route::get('shop', [PublicShopController::class, 'index'])->name('shop.index');
    Route::get('shop/product/{slug}', [PublicShopController::class, 'show'])->name('shop.show');

    // Admin Gateways Settings routes (authenticated via BotCMS middleware)
    Route::middleware(['web', 'botcms.auth:manage_settings'])->prefix('admin/botcommerce')->group(function () {
        Route::get('gateways', [AdminProductController::class, 'gateways'])->name('admin.botcommerce.gateways');
        Route::post('gateways', [AdminProductController::class, 'saveGateways'])->name('admin.botcommerce.gateways.save');
    });
});
