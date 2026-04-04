<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'webLogin']);
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->middleware('auth');

Route::fallback(function () {
    return view('welcome');
});
