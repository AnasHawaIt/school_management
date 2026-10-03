<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\FcmTokenController;
use Modules\Notifications\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum'])->prefix('Notifications')->group(function () {
    Route::get('/', [NotificationController::class,'index']);
    Route::get('/statistics', [NotificationController::class,'statistics']);
    Route::get('/{id}', [NotificationController::class,'show']);
    Route::get('/unread', [NotificationController::class, 'unread']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::patch('/{id}/restore', [NotificationController::class, 'restore']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/{id}/force', [NotificationController::class, 'forceDelete']);
    Route::post('/{id}/resend', [NotificationController::class, 'resend']);
    Route::post('/', [NotificationController::class, 'store']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('notifications/fcm-token', [FcmTokenController::class, 'update']);
});
