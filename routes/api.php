<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\Admin\TravelOrderController as AdminTravelOrderController;
use App\Http\Controllers\DestinationController;
use App\Http\Services\Notifications\TravelOrderNotificationService;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

//Routes without authentication
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

//Routes with authentication
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

    // Travel Orders Routes
    Route::prefix('travel-orders')->group(function () {
        Route::get('/', [TravelOrderController::class, 'index'])->name('orders.index');
        Route::post('/', [TravelOrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}', [TravelOrderController::class, 'show'])->name('orders.show');
        Route::put('/{order}', [TravelOrderController::class, 'update'])->name('orders.update');
    });

    // Destination Routes
    Route::prefix('destinations')->group(function () {
        Route::get('/states', [DestinationController::class, 'listStates'])->name('destinations.states');
        Route::get('/cities/{stateIbgeCode}', [DestinationController::class, 'listCitiesByState'])->name('destinations.cities');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['auth:api', 'is_admin'])->group(function () {

        // Admin Travel Orders Routes
        Route::prefix('travel-orders')->group(function () {
            Route::get('/', [AdminTravelOrderController::class, 'index'])->name('admin.orders.index');
            Route::get('/{order}', [AdminTravelOrderController::class, 'show'])->name('admin.orders.show');
            Route::put('/{order}', [AdminTravelOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        });
    });
});
