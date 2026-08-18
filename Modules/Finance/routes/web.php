<?php

use Illuminate\Support\Facades\Route;

// Finance is currently API-first. Keep this file available for future web routes.
Route::middleware(['web', 'auth'])->group(function () {
    // Add Finance web routes here when a server-rendered UI is introduced.
});
