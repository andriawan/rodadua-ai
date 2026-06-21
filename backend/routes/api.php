<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComparisonController;
use App\Http\Controllers\Api\MaintenanceController;
use App\Http\Controllers\Api\MotorcycleController;
use App\Http\Controllers\Api\TroubleshootingController;
use App\Http\Controllers\Api\WorkshopController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public authentication routes
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::get('/auth/user', [AuthController::class, 'user'])->name('auth.user');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Motorcycle CRUD
        Route::apiResource('motorcycles', MotorcycleController::class);
        Route::post('/motorcycles/{motorcycle}/toggle-favorite', [MotorcycleController::class, 'toggleFavorite'])->name('motorcycles.toggleFavorite');
        Route::put('/motorcycles/{motorcycle}/odometer', [MotorcycleController::class, 'updateOdometer'])->name('motorcycles.updateOdometer');

        // AI-Powered Troubleshooting (rate limited)
        Route::middleware('throttle:ai')->group(function () {
            Route::post('/troubleshooting/analyze', [TroubleshootingController::class, 'analyze'])->name('troubleshooting.analyze');
            Route::get('/troubleshooting/{motorcycle}/history', [TroubleshootingController::class, 'history'])->name('troubleshooting.history');

            Route::get('/maintenance/recommendations/{motorcycle}', [MaintenanceController::class, 'recommendations'])->name('maintenance.recommendations');
            Route::get('/maintenance/predict/{motorcycle}', [MaintenanceController::class, 'predict'])->name('maintenance.predict');
            Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

            Route::get('/motorcycles/{motorcycle}/compare/{comparedMotorcycle}', [ComparisonController::class, 'compare'])->name('comparison.compare');
            Route::get('/comparisons/{motorcycle}/history', [ComparisonController::class, 'history'])->name('comparison.history');
        });

        // Workshop search (no AI, no rate limit)
        Route::get('/workshops/search', [WorkshopController::class, 'search'])->name('workshops.search');
        Route::get('/workshops/{id}', [WorkshopController::class, 'show'])->name('workshops.show');
    });
});
