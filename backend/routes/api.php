<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MotorcycleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public authentication routes
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

    // Protected authentication routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'user'])->name('auth.user');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Motorcycle routes
        Route::apiResource('motorcycles', MotorcycleController::class);
        Route::post('/motorcycles/{motorcycle}/toggle-favorite', [MotorcycleController::class, 'toggleFavorite'])->name('motorcycles.toggleFavorite');
        Route::put('/motorcycles/{motorcycle}/odometer', [MotorcycleController::class, 'updateOdometer'])->name('motorcycles.updateOdometer');
    });
});
