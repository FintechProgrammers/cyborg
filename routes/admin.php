<?php

use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StrategyController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ManageAdministratorsController;
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
        Route::get('get-stattistics','getStatistics')->name('getStatistics');
    });

    Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{news}', 'show')->name('edit');
        Route::post('/edit/{news}', 'update')->name('update');
        Route::post('/publish/{news}', 'publish')->name('publish');
        Route::post('/unpublish/{news}', 'unpublish')->name('unpublish');
        Route::delete('/delete/{news}', 'destroy')->name('delete');
    });

    Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{banner}', 'show')->name('edit');
        Route::post('/edit/{banner}', 'update')->name('update');
        Route::post('/enable/{banner}', 'enableBanner')->name('enable');
        Route::post('/disable/{banner}', 'disableBanner')->name('disable');
        Route::delete('/delete/{banner}', 'destroy')->name('delete');
    });

    Route::controller(StrategyController::class)->prefix('bot')->name('bot.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{strategy}', 'show')->name('edit');
        Route::post('/edit/{strategy}', 'update')->name('update');
        Route::delete('/delete/{strategy}', 'destroy')->name('delete');
    });

    Route::controller(UserManagementController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/fund/{wallet}/{user}', 'fundForm')->name('fund');
        Route::get('/debit/{wallet}/{user}', 'debitForm')->name('debit');
        Route::post('/fund-wallet/{user}', 'fund')->name('fund.store');
        Route::post('/debit-wallet/{user}', 'debit')->name('debit.store');
        Route::get('/create/bot/{user}', 'createBotForm')->name('create.bot');
        Route::post('/create/bot/{user}', 'createBot')->name('create.bot.store');
        Route::post('/start/bot/{bot}', 'startBot')->name('bot.start');
        Route::post('/stop/bot/{bot}', 'stopBot')->name('bot.stop');
        Route::post('/delete/bot/{bot}', 'deleteBot')->name('bot.delete');
    });

    Route::controller(TransactionController::class)->prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{transaction}', 'show')->name('show');
        Route::get('/withdrawals/pending', 'withdrawals')->name('withdrawals.index');
        Route::post('/approve/{transaction}', 'approveTranasction')->name('approve');
    });

    Route::controller(TradeController::class)->prefix('trades')->name('trades.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{trades}', 'show')->name('show');
    });

    Route::controller(RolesController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('/edit/{role},', 'edit')->name('edit');
        Route::post('/update/{role},', 'update')->name('update');
        Route::delete('/{role}', 'destroy')->name('delete');
    });

    Route::controller(SupportController::class)->prefix('supports')->name('supports.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('show/{ticket}', 'show')->name('show');
        Route::post('reply/{ticket}', 'reply')->name('reply');
    });

    Route::controller(SettingsController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store');
    });

    Route::controller(ManageAdministratorsController::class)->prefix('administrators')->name('administrators.')->group(function () {
        Route::get('','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('create','store')->name('store');
        Route::get('/{admin}','show')->name('show');
        Route::post('update/{admin}','update')->name('update');
        Route::delete('{admin}','destroy')->name('delete');
    });
});
