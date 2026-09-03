<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\app\Http\Controllers\AnnouncementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('announcements', AnnouncementController::class)->names('announcement');
});

Route::view('/login', 'login');
