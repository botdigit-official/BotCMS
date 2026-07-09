<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicPageController;

Route::get('/', function () {
    return view('index');
});

Route::get('{slug}', [PublicPageController::class, 'show'])->name('page.show');
Route::get('{type}/{slug}', [PublicPageController::class, 'showPost'])->name('post.show');
