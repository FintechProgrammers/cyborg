<?php

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


Route::get('check', function () {

    $exchange = new \App\Services\Exchange\Binance("bjxi7DjSUz4UX5fjme0bLJzKjdOExpTaojuinIEn6d8LrTheY19xXcgUZM2j38SX", "Btuphpiklh9ejSXuuIlYbSBoppslwFxkXkAiBRjWVxjG5SF8YotPaVyLmYE8Gkpn");

    $balance = $exchange->getBalance();

    dd($balance);
});
