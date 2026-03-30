<?php

use Illuminate\Support\Facades\Route;
use Modules\School\Http\Controllers\AcademicYearController;
use Modules\School\Http\Controllers\SemesterController;
use Modules\School\Http\Controllers\GradeController;
use Modules\School\Http\Controllers\SchoolClassController;
use Modules\School\Http\Controllers\SectionController;
use Modules\School\Http\Controllers\HolidayController;

/*
|--------------------------------------------------------------------------
| School Module API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Academic Years
    Route::prefix('academic-years')->group(function () {
        Route::get('/', [AcademicYearController::class, 'index']);
        Route::post('/', [AcademicYearController::class, 'store']);
        Route::get('/current', [AcademicYearController::class, 'getCurrent']);
        Route::get('/{id}', [AcademicYearController::class, 'show']);
        Route::put('/{id}', [AcademicYearController::class, 'update']);
        Route::delete('/{id}', [AcademicYearController::class, 'destroy']);
        Route::post('/{id}/set-current', [AcademicYearController::class, 'setCurrent']);
    });

    // Semesters
    Route::prefix('semesters')->group(function () {
        Route::get('/', [SemesterController::class, 'index']);
        Route::post('/', [SemesterController::class, 'store']);
        Route::get('/current', [SemesterController::class, 'getCurrent']);
        Route::post('/{id}/set-current', [SemesterController::class, 'setCurrent']);
        Route::get('/{id}', [SemesterController::class, 'show']);
        Route::put('/{id}', [SemesterController::class, 'update']);
        Route::delete('/{id}', [SemesterController::class, 'destroy']);
    });

    // Grades
    Route::prefix('grades')->group(function () {
        Route::get('/', [GradeController::class, 'index']);
        Route::post('/', [GradeController::class, 'store']);
        Route::get('/{id}', [GradeController::class, 'show']);
        Route::put('/{id}', [GradeController::class, 'update']);
        Route::delete('/{id}', [GradeController::class, 'destroy']);
    });

    // Classes
    Route::prefix('classes')->group(function () {
        Route::get('/', [SchoolClassController::class, 'index']);
        Route::post('/', [SchoolClassController::class, 'store']);
        Route::get('/{id}', [SchoolClassController::class, 'show']);
        Route::put('/{id}', [SchoolClassController::class, 'update']);
        Route::delete('/{id}', [SchoolClassController::class, 'destroy']);
    });

    // Sections
    Route::prefix('sections')->group(function () {
        Route::get('/', [SectionController::class, 'index']);
        Route::post('/', [SectionController::class, 'store']);
        Route::get('/{id}', [SectionController::class, 'show']);
        Route::put('/{id}', [SectionController::class, 'update']);
        Route::delete('/{id}', [SectionController::class, 'destroy']);
    });

    // Holidays
    Route::prefix('holidays')->group(function () {
        Route::get('/', [HolidayController::class, 'index']);
        Route::post('/', [HolidayController::class, 'store']);
        Route::get('/upcoming', [HolidayController::class, 'getUpcoming']);
        Route::get('/{id}', [HolidayController::class, 'show']);
        Route::put('/{id}', [HolidayController::class, 'update']);
        Route::delete('/{id}', [HolidayController::class, 'destroy']);
    });
});
