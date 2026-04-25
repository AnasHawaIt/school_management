<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;


Route::prefix('announcement')->group(function () {

    Route::get('/', [AnnouncementController::class, 'Index']);
    Route::get('/AllOnlyTrashed', [AnnouncementController::class, 'AllOnlyTrashed']);
    Route::post('/', [AnnouncementController::class, 'Store']);
    Route::post('/{id}', [AnnouncementController::class, 'Update']);
    Route::delete('/{id}', [AnnouncementController::class, 'Destroy']);
    Route::post('/{id}/restore', [AnnouncementController::class, 'restore']);
    route::delete('/{id}/force', [AnnouncementController::class, 'forceDelete']);
});

