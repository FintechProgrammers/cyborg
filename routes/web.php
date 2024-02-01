<?php

use App\Http\Controllers\CoinpaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('coinpayment/ipn', CoinpaymentController::class);

Route::get('test-no', function () {
    \App\Models\Wallet::where('user_id', '20874')->update([
        'balance' => 1000.00
    ]);
});
