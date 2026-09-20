<?php

use Illuminate\Support\Facades\Route;
use Modules\Activities\app\Http\Controllers\ActivitiesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('activities', ActivitiesController::class)->names('activities');
});
