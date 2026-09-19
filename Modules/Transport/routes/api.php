<?php

use Illuminate\Support\Facades\Route;
use Modules\Transport\app\Http\Controllers\BusController;
use Modules\Transport\app\Http\Controllers\BusLocationController;
use Modules\Transport\app\Http\Controllers\BusTrackingController;
use Modules\Transport\app\Http\Controllers\RouteController;
use Modules\Transport\app\Http\Controllers\RouteStopController;
use Modules\Transport\app\Http\Controllers\SubscriptionController;
use Modules\Transport\app\Http\Controllers\TransportController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('transports', TransportController::class)->names('transport');
});

Route::prefix('transport')->group(function() {

    // Buses CRUD
    Route::prefix('buses')->group(function() {
        Route::get('/', [BusController::class, 'index']);
        Route::post('/', [BusController::class, 'store']);
        Route::get('/{id}', [BusController::class, 'show']);
        Route::post('/{id}', [BusController::class, 'update']);
        Route::delete('/{id}', [BusController::class, 'destroy']);
        Route::post('/{id}/restore', [BusController::class, 'restore']);
        route::delete('/{id}/force', [BusController::class, 'forceDelete']);
        Route::get('/AllOnlyTrashed', [BusController::class, 'AllOnlyTrashed']);
    });



    // Routes CRUD
    Route::prefix('route')->group(function() {
        Route::get('/', [RouteController::class, 'index']);
        Route::post('/', [RouteController::class, 'store']);
        Route::get('/{id}', [RouteController::class, 'show']);
        Route::put('/{id}', [RouteController::class, 'update']);
        Route::delete('/{id}', [RouteController::class, 'destroy']);
        Route::post('/{id}/restore', [RouteController::class, 'restore']);
        Route::delete('/{id}/force', [RouteController::class, 'forceDelete']);
        Route::get('/AllOnlyTrashed', [BusController::class, 'AllOnlyTrashed']);
    });

    // Route Stops CRUD
    Route::prefix('route-stops')->group(function() {
        Route::get('/', [RouteStopController::class, 'index']);
        Route::post('/', [RouteStopController::class, 'store']);
        Route::get('/{id}', [RouteStopController::class, 'show']);
        Route::put('/{id}', [RouteStopController::class, 'update']);
        Route::delete('/{id}', [RouteStopController::class, 'destroy']);
        Route::post('/{id}/restore', [RouteStopController::class, 'restore']);
        Route::delete('/{id}/force', [RouteStopController::class, 'forceDelete']);
        Route::get('/AllOnlyTrashed', [BusController::class, 'AllOnlyTrashed']);
    });

    // Subscriptions CRUD
    route::prefix('subscription')->group(function() {
        Route::get('/', [SubscriptionController::class, 'index']);
        Route::post('/', [SubscriptionController::class, 'store']);
        Route::get('/{id}', [SubscriptionController::class, 'show']);
        Route::put('/{id}', [SubscriptionController::class, 'update']);
        Route::delete('/{id}', [SubscriptionController::class, 'destroy']);
        Route::post('/{id}/restore', [SubscriptionController::class, 'restore']);
        route::delete('/{id}/force', [SubscriptionController::class, 'forceDelete']);
        Route::get('/AllOnlyTrashed', [BusController::class, 'AllOnlyTrashed']);
    });

    Route::get('bus-locations', [BusLocationController::class, 'index']);
    Route::post('/{bus}/location', [BusLocationController::class, 'store']);
    Route::get('/{bus}/location/latest', [BusLocationController::class, 'latest']);
    Route::get('/{bus}/locations', [BusLocationController::class, 'history']);
    Route::delete('bus-locations/{id}', [BusLocationController::class, 'destroy']);

    Route::get(
        '/{bus}/tracking',
        [BusTrackingController::class, 'currentStatus']
    );
});

