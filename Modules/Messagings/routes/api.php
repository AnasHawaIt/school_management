<?php

use Illuminate\Support\Facades\Route;
use Modules\Messagings\app\Http\Controllers\ConversationController;
use Modules\Messagings\app\Http\Controllers\MessageController;


Route::prefix('conversations')->middleware('auth:sanctum')->group(function () {
    Route::get('', [ConversationController::class, 'index']);
    Route::post('', [ConversationController::class, 'store']);
    Route::post('/{conversation}/join', [ConversationController::class, 'join']);
    Route::get('/{conversation}', [ConversationController::class, 'show']);
    Route::get('/{conversation}/messages', [MessageController::class, 'inbox']);
    Route::post('/{conversation}/messages', [MessageController::class, 'store']);
});

Route::prefix('Message')->middleware(["auth:sanctum"])->group(function () {
    Route::get('/inbox', [MessageController::class, 'index']);
    Route::get('/getSent', [MessageController::class, 'getSent']);
    Route::get('/{id}', [MessageController::class, 'show'])->whereNumber('id');
    Route::get('/unread-count', [MessageController::class, 'unreadCount']);
    Route::get('/AllOnlyTrashed', [MessageController::class, 'AllOnlyTrashed']);
    Route::post('/{id}/restore', [MessageController::class, 'restore']);
    Route::post('/{id}/reply', [MessageController::class, 'reply']);
    Route::post('/{id}/forward', [MessageController::class, 'forward']);
    Route::patch('/{id}/read', [MessageController::class, 'markAsRead']);
    route::delete('/{id}/force', [MessageController::class, 'forceDelete']);
    Route::delete('/{id}', [MessageController::class, 'delete']);
    Route::post('/{message}/attachments', [MessageController::class, 'uploadAttachment']);
    Route::delete('attachments/{id}', [MessageController::class, 'deleteAttachment']);
    Route::get('attachments/{id}', [MessageController::class, 'ShowAttachment']);
    Route::get('index', [MessageController::class, 'indexAttachment']);
});
