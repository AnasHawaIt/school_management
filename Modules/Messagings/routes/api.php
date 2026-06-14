<?php

use Illuminate\Support\Facades\Route;
use Modules\Messagings\app\Http\Controllers\MessageController;


Route::prefix('Message')->group(function () {
    Route::get('/inbox', [MessageController::class, 'inbox']);
    Route::get('/sent', [MessageController::class, 'sent']);
    Route::get('/{d}', [MessageController::class, 'show']);
    Route::get('/unread-count', [MessageController::class, 'unreadCount']);
    Route::get('/AllOnlyTrashed', [MessageController::class, 'AllOnlyTrashed']);
    Route::post('/', [MessageController::class, 'Store']);
    Route::post('/{id}/restore', [MessageController::class, 'restore']);
    Route::post('/{id}/reply', [MessageController::class, 'reply']);
    Route::post('/{id}/forward', [MessageController::class, 'forward']);
    Route::patch('/{id}/read', [MessageController::class, 'markAsRead']);
    route::delete('/{id}/force', [MessageController::class, 'forceDelete']);
    Route::delete('/{id}', [MessageController::class, 'destroy']);
    });
