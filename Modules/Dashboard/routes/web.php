<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Controllers\DashboardController;
use Modules\Dashboard\Controllers\PageController;
use Modules\Dashboard\Controllers\PostTypeController;

Route::middleware(['web', 'botcms.auth:view_dashboard'])->group(function () {
    Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::middleware('botcms.auth:manage_posts')->group(function () {
        Route::get('admin/pages', [PageController::class, 'index'])->name('admin.pages');
        Route::get('admin/pages/create', [PageController::class, 'create'])->name('admin.pages.create');
        Route::post('admin/pages/store', [PageController::class, 'store'])->name('admin.pages.store');
        Route::get('admin/pages/{id}/edit', [PageController::class, 'edit'])->name('admin.pages.edit');
        Route::post('admin/pages/{id}/update', [PageController::class, 'update'])->name('admin.pages.update');
        Route::post('admin/pages/{id}/delete', [PageController::class, 'destroy'])->name('admin.pages.delete');

        // Dynamic Custom Post Types Routes
        Route::get('admin/cpt/{type}', [PostTypeController::class, 'index'])->name('admin.cpt');
        Route::get('admin/cpt/{type}/create', [PostTypeController::class, 'create'])->name('admin.cpt.create');
        Route::post('admin/cpt/{type}/store', [PostTypeController::class, 'store'])->name('admin.cpt.store');
        Route::get('admin/cpt/{type}/{id}/edit', [PostTypeController::class, 'edit'])->name('admin.cpt.edit');
        Route::post('admin/cpt/{type}/{id}/update', [PostTypeController::class, 'update'])->name('admin.cpt.update');
        Route::post('admin/cpt/{type}/{id}/delete', [PostTypeController::class, 'destroy'])->name('admin.cpt.delete');
    });
    
    Route::middleware('botcms.auth:manage_themes')->group(function () {
        Route::get('admin/themes', [DashboardController::class, 'themes'])->name('admin.themes');
        Route::post('admin/themes/activate/{theme}', [DashboardController::class, 'activateTheme'])->name('admin.themes.activate');
        Route::post('admin/themes/upload', [DashboardController::class, 'uploadTheme'])->name('admin.themes.upload');
    });

    Route::middleware('botcms.auth:manage_plugins')->group(function () {
        Route::get('admin/plugins', [DashboardController::class, 'plugins'])->name('admin.plugins');
        Route::post('admin/plugins/toggle/{plugin}', [DashboardController::class, 'togglePlugin'])->name('admin.plugins.toggle');
        Route::post('admin/plugins/upload', [DashboardController::class, 'uploadPlugin'])->name('admin.plugins.upload');
    });
    
    Route::middleware('botcms.auth:manage_settings')->group(function () {
        Route::get('admin/settings', [DashboardController::class, 'settings'])->name('admin.settings');
        Route::post('admin/settings', [DashboardController::class, 'saveSettings'])->name('admin.settings.save');
    });
});
