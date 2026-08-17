<?php

use Illuminate\Support\Facades\Route;

use Modules\Activities\Http\Controllers\ActivityController;
use Modules\Activities\Http\Controllers\ActivityParticipantController;
use Modules\Activities\Http\Controllers\ActivitySupervisorController;
use Modules\Activities\Http\Controllers\ActivityAttachmentController;

Route::middleware('auth:sanctum')
    ->prefix('activities')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Activities
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            ActivityController::class,
            'index'
        ]);

        Route::post('/', [
            ActivityController::class,
            'store'
        ]);

        Route::get('/{activity}', [
            ActivityController::class,
            'show'
        ]);

        Route::put('/{activity}', [
            ActivityController::class,
            'update'
        ]);

        Route::delete('/{activity}', [
            ActivityController::class,
            'destroy'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Status
        |--------------------------------------------------------------------------
        */

        Route::post('/{activity}/publish', [
            ActivityController::class,
            'publish'
        ]);

        Route::post('/{activity}/start', [
            ActivityController::class,
            'start'
        ]);

        Route::post('/{activity}/cancel', [
            ActivityController::class,
            'cancel'
        ]);

        Route::post('/{activity}/complete', [
            ActivityController::class,
            'complete'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        */

        Route::post('/{activity}/participants', [
            ActivityParticipantController::class,
            'register'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Supervisors
        |--------------------------------------------------------------------------
        */

        Route::post('/{activity}/supervisors', [
            ActivitySupervisorController::class,
            'store'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attachments
        |--------------------------------------------------------------------------
        */

        Route::post('/{activity}/attachments', [
            ActivityAttachmentController::class,
            'store'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Participant Actions
        |--------------------------------------------------------------------------
        */

        Route::post('/participants/{participant}/confirm', [
            ActivityParticipantController::class,
            'confirm'
        ]);

        Route::post('/participants/{participant}/cancel', [
            ActivityParticipantController::class,
            'cancel'
        ]);

        Route::post('/participants/{participant}/attend', [
            ActivityParticipantController::class,
            'attend'
        ]);

        Route::post('/participants/{participant}/absent', [
            ActivityParticipantController::class,
            'absent'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Supervisor Actions
        |--------------------------------------------------------------------------
        */

        Route::post('/supervisors/{supervisor}/primary', [
            ActivitySupervisorController::class,
            'primary'
        ]);

        Route::delete('/supervisors/{supervisor}', [
            ActivitySupervisorController::class,
            'destroy'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attachments Actions
        |--------------------------------------------------------------------------
        */

        Route::delete('/attachments/{attachment}', [
            ActivityAttachmentController::class,
            'destroy'
        ]);
    });
