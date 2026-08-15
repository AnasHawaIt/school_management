<?php

use Illuminate\Support\Facades\Route;
use Modules\Activities\Http\Controllers\ActivitiesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('activities', ActivitiesController::class)->names('activities');
});
