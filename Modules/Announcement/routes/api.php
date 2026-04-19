<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;


Route::prefix('announcement')->group(function () {

    Route::get('/', [AnnouncementController::class, 'Index']);
  //  Route::get('/test-sms', [AnnouncementController::class, 'test']);
    Route::post('/', [AnnouncementController::class, 'Store']);
    Route::post('/{id}', [AnnouncementController::class, 'Update']);
    Route::delete('/{id}', [AnnouncementController::class, 'Destroy']);
    Route::delete('{id}', [AnnouncementController::class, 'destroy']);
    Route::post('/{id}/restore', [AnnouncementController::class, 'restore']);
    route::delete('/{id}/force', [AnnouncementController::class, 'forceDelete']);
});

