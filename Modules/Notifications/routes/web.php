<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\NotificationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('notifications', NotificationController::class)->names('notifications');
});
