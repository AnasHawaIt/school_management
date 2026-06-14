<?php

use Illuminate\Support\Facades\Route;
use Modules\SMS\app\Http\Controllers\SMSController;
use Modules\SMS\Jobs\SendSmsJob;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sms', SMSController::class)->names('sms');
});

Route::prefix('SmsOtps')->group(function () {

    Route::post('/send', [SMSController::class, 'send']);

    Route::post('/verify', [SMSController::class, 'verify']);

});



