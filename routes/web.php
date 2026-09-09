<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Messaging;
use Modules\Library\app\Http\Controllers\LibraryDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/library/dashboard', LibraryDashboardController::class)
        ->middleware('permission:library.catalog.view')
        ->name('library.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/firebase-test', function (Messaging $messaging) {
    return response()->json([
        'status' => 'Firebase Connected'
    ]);
});
