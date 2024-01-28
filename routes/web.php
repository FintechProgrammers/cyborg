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
    return view('admin.authentication.login');
});

Route::post('coinpayment/ipn', CoinpaymentController::class);

Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('cron', function () {
    return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('cron');
