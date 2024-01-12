<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\TradeRecordsController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\ExchangeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('validate.user')->group(function () {

    Route::prefix('wallet')->group(function () {

        Route::controller(WalletController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/transfer', 'transfer');
            Route::get('/transactions', 'transactions');
        });

        Route::post('deposit', DepositController::class);
        Route::post('withdraw', WithdrawalController::class);
    });

    Route::controller(ExchangeController::class)->prefix('exchanges')->group(function () {
        Route::get('/', 'index');
        Route::get('/binded', 'bindedExchange');
        Route::post('/bind', 'bind');
        Route::get('/markets', 'markets');
    });

    Route::prefix('history')->group(function () {
        Route::get('/trades', [TradeRecordsController::class, 'index']);
        Route::get('/profits', [TradeRecordsController::class, 'profitRecord']);
        Route::get('/rewards', [TradeRecordsController::class, 'rewards']);
    });

    Route::controller(BotController::class)->prefix('bots')->group(function () {
        Route::get('', 'index');
        Route::get('/details/{bot}', 'show');
        Route::post('/create', 'create');
        Route::patch('/update/{bot}', 'update');
        Route::post('start', 'startBot');
        Route::post('stop', 'stopBot');
        Route::delete('/{bot}', 'destroy');
    });

});

Route::get('banners', [BannerController::class, 'banners']);

Route::controller(\App\Http\Controllers\Api\NewsController::class)->prefix('news')->group(function () {
    Route::get('', 'index');
});
