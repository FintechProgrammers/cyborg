<?php

use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\DashBoardController;
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
        Route::get('/','index')->name('index');
    });
});
