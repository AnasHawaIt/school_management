<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\app\Http\Controllers\LeaveRequestController;
use Modules\Attendance\app\Http\Controllers\StudentAttendanceController;
use Modules\Attendance\app\Http\Controllers\TeacherAttendanceController;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('student-attendance')->group(function () {
        Route::get('/',        [StudentAttendanceController::class, 'index']);
        Route::post('/',       [StudentAttendanceController::class, 'store']);
        Route::post('/bulk',   [StudentAttendanceController::class, 'bulk']);
        Route::get('/{id}',    [StudentAttendanceController::class, 'show']);
        Route::put('/{id}',    [StudentAttendanceController::class, 'update']);
        Route::delete('/{id}', [StudentAttendanceController::class, 'destroy']);
    });

    Route::prefix('sections/{section}')->group(function () {
        Route::get('/attendance',       [StudentAttendanceController::class, 'sectionAttendance']);
        Route::get('/attendance-stats', [StudentAttendanceController::class, 'sectionStats']);
    });

    Route::prefix('students/{student}')->group(function () {
        Route::get('/attendance-report', [StudentAttendanceController::class, 'studentReport']);
        Route::get('/attendance-stats',  [StudentAttendanceController::class, 'studentStats']);
    });

    Route::prefix('teacher-attendance')->group(function () {
        Route::get('/',        [TeacherAttendanceController::class, 'index']);
        Route::get('/daily',   [TeacherAttendanceController::class, 'daily']);
        Route::post('/',       [TeacherAttendanceController::class, 'store']);
        Route::get('/{id}',    [TeacherAttendanceController::class, 'show']);
        Route::put('/{id}',    [TeacherAttendanceController::class, 'update']);
        Route::delete('/{id}', [TeacherAttendanceController::class, 'destroy']);
    });

    Route::prefix('teachers/{teacher}')->group(function () {
        Route::get('/attendance-report', [TeacherAttendanceController::class, 'teacherReport']);
        Route::get('/attendance-stats',  [TeacherAttendanceController::class, 'teacherStats']);
    });

    Route::prefix('leave-requests')->group(function () {
        Route::get('/',              [LeaveRequestController::class, 'index']);
        Route::get('/pending',       [LeaveRequestController::class, 'pending']);
        Route::post('/',             [LeaveRequestController::class, 'store']);
        Route::get('/{id}',          [LeaveRequestController::class, 'show']);
        Route::put('/{id}',          [LeaveRequestController::class, 'update']);
        Route::delete('/{id}',       [LeaveRequestController::class, 'destroy']);
        Route::post('/{id}/approve', [LeaveRequestController::class, 'approve']);
        Route::post('/{id}/reject',  [LeaveRequestController::class, 'reject']);
    });
});
