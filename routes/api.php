<?php

use App\Http\Controllers\Api\ApiKeyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('rooms', [RoomController::class, 'index']);
    Route::get('rooms/{room}', [RoomController::class, 'show']);
    Route::post('rooms', [RoomController::class, 'store']);
    Route::put('rooms/{room}', [RoomController::class, 'update']);
    Route::delete('rooms/{room}', [RoomController::class, 'destroy']);
    Route::get('rooms/{room}/availability', [RoomController::class, 'availability']);

    Route::get('bookings', [BookingController::class, 'index']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::put('bookings/{booking}', [BookingController::class, 'update']);
    Route::patch('bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy']);

    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [RoomController::class, 'stats']);
        Route::get('rooms', [RoomController::class, 'index']);
        Route::post('rooms', [RoomController::class, 'store']);
        Route::put('rooms/{room}', [RoomController::class, 'update']);
        Route::delete('rooms/{room}', [RoomController::class, 'destroy']);
        Route::get('bookings', [BookingController::class, 'index']);
        Route::post('bookings', [BookingController::class, 'store']);
        Route::put('bookings/{booking}', [BookingController::class, 'update']);
        Route::patch('bookings/{booking}', [BookingController::class, 'update']);
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy']);
        Route::get('api-keys', [ApiKeyController::class, 'index']);
        Route::post('api-keys', [ApiKeyController::class, 'store']);
        Route::patch('api-keys/{apiKey}', [ApiKeyController::class, 'update']);
        Route::delete('api-keys/{apiKey}', [ApiKeyController::class, 'destroy']);
    });
});
