<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\app\Http\Controllers\LibraryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('libraries', LibraryController::class)->names('library');
});
