<?php

use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\StrategyController;
use App\Models\Strategy;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/', 'login')->name('login.submit');
    Route::get('/forgot-password', 'forgotPasswordForm')->name('forgot.password');
    Route::post('/forgot-password', 'forgotPassword')->name('forgot.password.post');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::controller(DashBoardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{news}', 'show')->name('edit');
        Route::post('/edit/{news}', 'update')->name('update');
        Route::delete('/delete/{news}', 'destroy')->name('delete');
    });

    Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{banner}', 'show')->name('edit');
        Route::post('/edit/{banner}', 'update')->name('update');
        Route::delete('/delete/{banner}', 'destroy')->name('delete');
    });

    Route::controller(StrategyController::class)->prefix('bot')->name('bot.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{banner}', 'show')->name('edit');
        Route::post('/edit/{banner}', 'update')->name('update');
        Route::delete('/delete/{banner}', 'destroy')->name('delete');
    });
});
