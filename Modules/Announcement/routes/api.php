<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;

Route::prefix('announcement')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // =========================
    // Special GET endpoints
    // =========================

    Route::get('/published', [
        AnnouncementController::class,
        'indexPublished'
    ])->middleware('permission:announcements.view');

    Route::get('/scheduled', [
        AnnouncementController::class,
        'indexScheduled'
    ])->middleware('permission:announcements.view');

    Route::get('/expired', [
        AnnouncementController::class,
        'indexExpired'
    ])->middleware('permission:announcements.view');

    Route::get('/Pinned', [
        AnnouncementController::class,
        'indexPinned'
    ])->middleware('permission:announcements.view');

    Route::get('/trashed', [
        AnnouncementController::class,
        'onlyTrashed'
    ])->middleware('permission:announcements.view');

    // =========================
    // Basic CRUD
    // =========================

    Route::get('/', [
        AnnouncementController::class,
        'index'
    ])->middleware('permission:announcements.view');

    Route::post('/', [
        AnnouncementController::class,
        'store'
    ])->middleware('permission:announcements.create');

    Route::get('/{id}', [
        AnnouncementController::class,
        'show'
    ])->middleware('permission:announcements.view');

    Route::post('/{id}', [
        AnnouncementController::class,
        'update'
    ])->middleware('permission:announcements.update');

    Route::delete('/{id}', [
        AnnouncementController::class,
        'destroy'
    ])->middleware('permission:announcements.delete');

    // =========================
    // Announcement actions
    // =========================

    Route::post('/{id}/publish', [
        AnnouncementController::class,
        'publish'
    ])->middleware('permission:announcements.publish');

    Route::post('/{id}/pin', [
        AnnouncementController::class,
        'pin'
    ])->middleware('permission:announcements.pin');

    Route::post('/{id}/unpin', [
        AnnouncementController::class,
        'unpin'
    ])->middleware('permission:announcements.pin');

    Route::post('/{id}/cancel', [
        AnnouncementController::class,
        'cancel'
    ])->middleware('permission:announcements.publish');

    Route::post('/{id}/schedule', [
        AnnouncementController::class,
        'schedule'
    ])->middleware('permission:announcements.publish');

    Route::post('/{id}/expire', [
        AnnouncementController::class,
        'expire'
    ])->middleware('permission:announcements.publish');

    // =========================
    // Restore / Force Delete
    // =========================

    Route::post('/{id}/restore', [
        AnnouncementController::class,
        'restore'
    ])->middleware('permission:announcements.update');

    Route::delete('/{id}/force', [
        AnnouncementController::class,
        'forceDelete'
    ])->middleware('permission:announcements.force_delete');
});
