<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ArticleImageController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WritingController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/work', [WorkController::class, 'index'])->name('work.index');

Route::get('/writing', [WritingController::class, 'index'])->name('writing.index');
Route::get('/writing/{article:slug}', [WritingController::class, 'show'])->name('writing.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/feed.xml', FeedController::class)->name('feed');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', OverviewController::class)->name('overview');

        Route::resource('products', ProductController::class)->except('show');
        Route::resource('articles', ArticleController::class)->except('show');
        Route::get('/articles/{article}/preview', [ArticleController::class, 'preview'])->name('articles.preview');
        Route::patch('/articles/{article}/toggle-publish', [ArticleController::class, 'togglePublish'])->name('articles.toggle-publish');
        Route::post('/articles/images', [ArticleImageController::class, 'store'])->name('articles.images.store');
    });
});
