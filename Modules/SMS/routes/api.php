<?php

use Illuminate\Support\Facades\Route;
use Modules\SMS\app\Http\Controllers\SMSController;
use Modules\SMS\Jobs\SendSmsJob;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sms', SMSController::class)->names('sms');
});

