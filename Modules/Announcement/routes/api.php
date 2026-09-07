<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;

Route::prefix('announcement')->middleware('auth:sanctum')->group(function () {

    // =========================
    // Special GET endpoints
    // =========================

    Route::get('/published', [
        AnnouncementController::class,
        'indexPublished'
    ]);

    Route::get('/trashed', [
        AnnouncementController::class,
        'onlyTrashed'
    ]);

    // =========================
    // Basic CRUD
    // =========================

    Route::get('/', [
        AnnouncementController::class,
        'index'
    ]);

    Route::post('/', [
        AnnouncementController::class,
        'store'
    ]);

    Route::get('/{id}', [
        AnnouncementController::class,
        'show'
    ]);

    Route::post('/{id}', [
        AnnouncementController::class,
        'update'
    ]);

    Route::delete('/{id}', [
        AnnouncementController::class,
        'destroy'
    ]);

    // =========================
    // Announcement actions
    // =========================

    Route::post('/{id}/publish', [
        AnnouncementController::class,
        'publish'
    ]);

    Route::post('/{id}/schedule', [
        AnnouncementController::class,
        'schedule'
    ]);

    Route::post('/{id}/expire', [
        AnnouncementController::class,
        'expire'
    ]);

    // =========================
    // Restore / Force Delete
    // =========================

    Route::post('/{id}/restore', [
        AnnouncementController::class,
        'restore'
    ]);

    Route::delete('/{id}/force', [
        AnnouncementController::class,
        'forceDelete'
    ]);
});
