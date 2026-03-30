<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\TeacherController;
use Modules\Academic\Http\Controllers\StudentController;
use Modules\Academic\Http\Controllers\GuardianController;
use Modules\Academic\Http\Controllers\SubjectController;
use Modules\Academic\Http\Controllers\TimetableController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // ==================== Teachers ====================
    Route::prefix('teachers')->group(function () {
        Route::get('/',                                  [TeacherController::class, 'index']);
        Route::post('/',                                 [TeacherController::class, 'store']);
        Route::get('/{teacher}',                         [TeacherController::class, 'show']);
        Route::put('/{teacher}',                         [TeacherController::class, 'update']);
        Route::delete('/{teacher}',                      [TeacherController::class, 'destroy']);
        Route::post('/{teacher}/restore',                [TeacherController::class, 'restore']);
        Route::patch('/{teacher}/toggle-status',         [TeacherController::class, 'toggleStatus']);
        Route::get('/{teacher}/qualifications',          [TeacherController::class, 'qualifications']);
        Route::post('/{teacher}/qualifications',         [TeacherController::class, 'addQualification']);
        Route::delete('/qualifications/{qualification}', [TeacherController::class, 'deleteQualification']);
        Route::get('/{teacher}/timetable',               [TeacherController::class, 'timetable']);
    });

    // ==================== Students ====================
    Route::prefix('students')->group(function () {
        Route::get('/',                         [StudentController::class, 'index']);
        Route::post('/',                        [StudentController::class, 'store']);
        Route::post('/promote',                 [StudentController::class, 'promote']);
        Route::get('/{student}',                [StudentController::class, 'show']);
        Route::put('/{student}',                [StudentController::class, 'update']);
        Route::delete('/{student}',             [StudentController::class, 'destroy']);
        Route::post('/{student}/restore',       [StudentController::class, 'restore']);
        Route::post('/{student}/transfer',      [StudentController::class, 'transfer']);
        Route::patch('/{student}/status',       [StudentController::class, 'updateStatus']);
        Route::get('/{student}/parents',        [StudentController::class, 'parents']);
        Route::get('/{student}/medical-record', [StudentController::class, 'medicalRecord']);
        Route::put('/{student}/medical-record', [StudentController::class, 'updateMedicalRecord']);
    });

    // Students by section
    Route::prefix('sections/{section}')->group(function () {
        Route::get('/students',       [StudentController::class, 'bySection']);
        Route::get('/students/stats', [StudentController::class, 'sectionStats']);
        Route::get('/timetable',      [TimetableController::class, 'sectionTimetable']);
    });

    // ==================== Guardians ====================
    Route::prefix('guardians')->group(function () {
        Route::get('/',                                        [GuardianController::class, 'index']);
        Route::post('/',                                       [GuardianController::class, 'store']);
        Route::get('/{guardian}',                              [GuardianController::class, 'show']);
        Route::put('/{guardian}',                              [GuardianController::class, 'update']);
        Route::delete('/{guardian}',                           [GuardianController::class, 'destroy']);
        Route::post('/{guardian}/restore',                     [GuardianController::class, 'restore']);
        Route::get('/{guardian}/students',                     [GuardianController::class, 'students']);
        Route::post('/{guardian}/attach-student',              [GuardianController::class, 'attachStudent']);
        Route::delete('/{guardian}/detach-student/{student}',  [GuardianController::class, 'detachStudent']);
    });

    // ==================== Subjects ====================
    Route::prefix('subjects')->group(function () {
        Route::get('/',                              [SubjectController::class, 'index']);
        Route::post('/',                             [SubjectController::class, 'store']);
        Route::get('/{subject}',                     [SubjectController::class, 'show']);
        Route::put('/{subject}',                     [SubjectController::class, 'update']);
        Route::delete('/{subject}',                  [SubjectController::class, 'destroy']);
        Route::post('/{subject}/restore',            [SubjectController::class, 'restore']);
        Route::patch('/{subject}/toggle-status',     [SubjectController::class, 'toggleStatus']);
        Route::get('/{subject}/teachers',            [SubjectController::class, 'teachers']);
        Route::post('/{subject}/assign-teacher',     [SubjectController::class, 'assignTeacher']);
        Route::delete('/{subject}/unassign-teacher', [SubjectController::class, 'unassignTeacher']);
    });

    Route::get('/grades/{grade}/subjects', [SubjectController::class, 'byGrade']);

    // ==================== Timetables ====================
    Route::prefix('timetables')->group(function () {
        Route::get('/',               [TimetableController::class, 'index']);
        Route::post('/',              [TimetableController::class, 'store']);
        Route::get('/{timetable}',    [TimetableController::class, 'show']);
        Route::put('/{timetable}',    [TimetableController::class, 'update']);
        Route::delete('/{timetable}', [TimetableController::class, 'destroy']);
    });

    Route::get('/teachers/{teacher}/timetable', [TimetableController::class, 'teacherTimetable']);
});
