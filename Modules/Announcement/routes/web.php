<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('announcements', AnnouncementController::class)
        ->names('announcement')
        ->middlewareFor(['index', 'show'], 'permission:announcements.view')
        ->middlewareFor('store', 'permission:announcements.create')
        ->middlewareFor('update', 'permission:announcements.update')
        ->middlewareFor('destroy', 'permission:announcements.delete');
});
