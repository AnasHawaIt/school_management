<?php

use Illuminate\Support\Facades\Route;
use Modules\Messagings\app\Http\Controllers\ConversationController;
use Modules\Messagings\app\Http\Controllers\MessageController;


Route::prefix('conversations')->middleware('auth:sanctum')->group(function () {
    Route::get('', [ConversationController::class, 'index']);
    Route::post('', [ConversationController::class, 'store']);
    Route::delete('/{conversation}', [ConversationController::class, 'delete']);
    Route::get('/{conversation}', [ConversationController::class, 'show']);
    Route::post('/{conversation}/join', [ConversationController::class, 'join']);
    Route::post('/{conversation}/leave', [ConversationController::class, 'leave']);
    Route::post('/{id}/removeUser/{userId}', [ConversationController::class, 'removeParticipant']);
    Route::post('/{id}/addUser/request', [ConversationController::class, 'addParticipant']);
    Route::post('/{conversation}/addAdmin/{user}', [ConversationController::class, 'addAdmin']);
    Route::delete('/{conversation}/removeAdmin/{user}', [ConversationController::class, 'removeAdmin']);
    Route::get('/{conversation}/messages', [MessageController::class, 'inbox']);
    Route::get('/{conversation}/AllOnlyTrashed', [MessageController::class, 'AllOnlyTrashed']);
    Route::post('/{conversation}/message/{id}/restore', [MessageController::class, 'restore']);
    Route::delete('/{conversation}/attachments/{id}', [MessageController::class, 'deleteAttachment']);
    Route::delete('/{conversation}/message/{id}/', [MessageController::class, 'delete']);
    Route::post('/{conversation}/messages', [MessageController::class, 'store']);
    route::delete('/{conversation}/message/{id}/force', [MessageController::class, 'forceDelete']);
    Route::post('/{conversation}/voice', [MessageController::class, 'sendVoice']);
});

Route::prefix('Message')->middleware(["auth:sanctum"])->group(function () {
    Route::get('/inbox', [MessageController::class, 'index']);
    Route::get('/getSent', [MessageController::class, 'getSent']);
    Route::get('/{id}', [MessageController::class, 'show'])->whereNumber('id');
    Route::get('/unread-count', [MessageController::class, 'unreadCount']);
    Route::post('/{id}/reply', [MessageController::class, 'reply']);
    Route::post('/{id}/forward', [MessageController::class, 'forward']);
    Route::patch('/{id}/read', [MessageController::class, 'markAsRead']);
    Route::delete('/{id}', [MessageController::class, 'delete']);
    Route::post('/{message}/attachments', [MessageController::class, 'uploadAttachment']);
    Route::get('attachments/{id}', [MessageController::class, 'ShowAttachment']);
    Route::get('index', [MessageController::class, 'indexAttachment']);
});
