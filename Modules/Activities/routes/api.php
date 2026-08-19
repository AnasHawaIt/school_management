<?php

use Illuminate\Support\Facades\Route;

use Modules\Activities\Http\Controllers\ActivityController;
use Modules\Activities\Http\Controllers\ActivityParticipantController;
use Modules\Activities\Http\Controllers\ActivitySupervisorController;
use Modules\Activities\Http\Controllers\ActivityAttachmentController;

Route::middleware('auth:sanctum')
    ->prefix('activities')
    ->group(function () {
    Route::get('/', [ActivityController::class, 'index']);
    Route::post('/', [ActivityController::class, 'store']);
    Route::get('/{id}', [ActivityController::class, 'show']);
    Route::post('/{id}', [ActivityController::class, 'update']);
    Route::delete('/{id}', [ActivityController::class, 'destroy']);
    Route::post('/{activity}/publish', [ActivityController::class, 'publish']);
    Route::post('/{activity}/start', [ActivityController::class, 'start']);
    Route::post('/{activity}/cancel', [ActivityController::class, 'cancel']);
    Route::post('/{activity}/complete', [ActivityController::class, 'complete']);
    Route::post('/{activity}/participants', [ActivityParticipantController::class, 'register']);
    Route::post('/{activity}/supervisors', [ActivitySupervisorController::class, 'store']);
    Route::post('/{activity}/attachments', [ActivityAttachmentController::class, 'store']);
    });

Route::middleware('auth:sanctum')
    ->prefix('participants')
    ->group(function () {
        Route::post('/{id}/confirm', [ActivityParticipantController::class, 'confirm']);
        Route::post('/{id}/cancel', [ActivityParticipantController::class, 'cancel']);
        Route::post('/{id}/attend', [ActivityParticipantController::class, 'attend']);
        Route::post('/{id}/absent', [ActivityParticipantController::class, 'absent']);
});

Route::middleware('auth:sanctum')
    ->prefix('supervisors')
    ->group(function () {
        Route::post('/{id}/primary', [ActivitySupervisorController::class, 'primary']);
        Route::delete('/{id}', [ActivitySupervisorController::class, 'destroy']);
    });

Route::middleware('auth:sanctum')
    ->prefix('attachments')
    ->group(function () {
        Route::post('/{id}/upload', [ActivityAttachmentController::class, 'store']);
        Route::delete('/{id}', [ActivityAttachmentController::class, 'destroy']);
    });
