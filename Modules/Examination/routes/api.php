<?php

use Illuminate\Support\Facades\Route;
use Modules\Examination\Http\Controllers\ExamController;
use Modules\Examination\Http\Controllers\ExamResultController;
use Modules\Examination\Http\Controllers\ReportCardController;

Route::middleware(['auth:sanctum'])->group(function () {

    // ==================== Exams ====================
    Route::prefix('exams')->group(function () {
        Route::get('/',                  [ExamController::class, 'index']);
        Route::post('/',                 [ExamController::class, 'store']);
        Route::get('/{exam}',            [ExamController::class, 'show']);
        Route::put('/{exam}',            [ExamController::class, 'update']);
        Route::delete('/{exam}',         [ExamController::class, 'destroy']);
        Route::post('/{exam}/restore',   [ExamController::class, 'restore']);
        Route::patch('/{exam}/status',   [ExamController::class, 'updateStatus']);

        Route::get('/{exam}/results',       [ExamResultController::class, 'examResults']);
        Route::post('/{exam}/results/bulk', [ExamResultController::class, 'bulk']);
        Route::get('/{exam}/stats',         [ExamResultController::class, 'examStats']);
    });


    Route::get('/sections/{section}/exams', [ExamController::class, 'sectionExams']);


    Route::get('/teachers/{teacher}/exams', [ExamController::class, 'teacherExams']);

    // ==================== Exam Results ====================
    Route::prefix('exam-results')->group(function () {
        Route::get('/',     [ExamResultController::class, 'index']);
        Route::post('/',    [ExamResultController::class, 'store']);
        Route::get('/{id}', [ExamResultController::class, 'show']);
        Route::put('/{id}', [ExamResultController::class, 'update']);
    });


    Route::prefix('students/{student}')->group(function () {
        Route::get('/results',    [ExamResultController::class, 'studentResults']);
        Route::get('/exam-stats', [ExamResultController::class, 'studentStats']);
        Route::get('/report-card',[ReportCardController::class, 'studentReportCard']);
    });

    // ==================== Report Cards ====================
    Route::prefix('report-cards')->group(function () {
        Route::get('/',     [ReportCardController::class, 'index']);
        Route::get('/{id}', [ReportCardController::class, 'show']);
        Route::put('/{id}', [ReportCardController::class, 'update']);
        Route::patch('/{id}/publish', [ReportCardController::class, 'publish']);
    });


    Route::prefix('sections/{section}/report-cards')->group(function () {
        Route::get('/',             [ReportCardController::class, 'sectionReportCards']);
        Route::post('/generate',    [ReportCardController::class, 'generate']);
        Route::post('/publish-all', [ReportCardController::class, 'publishAll']);
    });
});
