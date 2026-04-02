<?php

use Illuminate\Support\Facades\Route;
use Modules\Transport\Http\Controllers\TransportController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('transports', TransportController::class)->names('transport');
});
