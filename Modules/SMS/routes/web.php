<?php

use Illuminate\Support\Facades\Route;
use Modules\SMS\app\Http\Controllers\SMSController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('sms', SMSController::class)->names('sms');
});
