<?php

use Illuminate\Support\Facades\Route;
use Modules\Transport\Http\Controllers\BusController;
use Modules\Transport\Http\Controllers\RouteController;
use Modules\Transport\Http\Controllers\RouteStopController;
use Modules\Transport\Http\Controllers\SubscriptionController;
use Modules\Transport\Http\Controllers\TransportController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('transports', TransportController::class)->names('transport');
});

Route::prefix('transport')->group(function() {

    // Buses CRUD
    Route::get('buses', [BusController::class, 'index']);
    Route::post('buses', [BusController::class, 'store']);
    Route::get('buses/{id}', [BusController::class, 'show']);
    Route::put('buses/{id}', [BusController::class, 'update']);
    Route::delete('buses/{id}', [BusController::class, 'destroy']);

    // Routes CRUD
    Route::get('routes', [RouteController::class, 'index']);
    Route::post('routes', [RouteController::class, 'store']);
    Route::get('routes/{id}', [RouteController::class, 'show']);
    Route::put('routes/{id}', [RouteController::class, 'update']);
    Route::delete('routes/{id}', [RouteController::class, 'destroy']);

    // Route Stops CRUD
    Route::get('route-stops', [RouteStopController::class, 'index']);
    Route::post('route-stops', [RouteStopController::class, 'store']);
    Route::get('route-stops/{id}', [RouteStopController::class, 'show']);
    Route::put('route-stops/{id}', [RouteStopController::class, 'update']);
    Route::delete('route-stops/{id}', [RouteStopController::class, 'destroy']);

    // Subscriptions CRUD
    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::post('subscriptions', [SubscriptionController::class, 'store']);
    Route::get('subscriptions/{id}', [SubscriptionController::class, 'show']);
    Route::put('subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('subscriptions/{id}', [SubscriptionController::class, 'destroy']);
});
