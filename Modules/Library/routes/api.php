<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\app\Http\Controllers\AuthorController;
use Modules\Library\app\Http\Controllers\BookController;
use Modules\Library\app\Http\Controllers\BookCopyController;
use Modules\Library\app\Http\Controllers\CategoryController;
use Modules\Library\app\Http\Controllers\LibraryController;
use Modules\Library\app\Http\Controllers\MemberController;
use Modules\Library\app\Http\Controllers\PublishersController;
use Modules\Library\app\Http\Controllers\TransactionController;
use Modules\Library\app\Http\Controllers\FineController;
use Modules\Library\app\Http\Controllers\ReservationController;
use Modules\Library\app\Http\Controllers\LibraryReportController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('libraries', LibraryController::class)->names('library');
});

Route::middleware(['auth:sanctum'])->prefix('library')->group(function() {
    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::get('/AllOnlyTrashed', [AuthorController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [AuthorController::class, 'show'])->middleware('permission:library.catalog.view');
        Route::post('/', [AuthorController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::post('/Update/{id}', [AuthorController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{id}', [AuthorController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::post('/{id}/restore', [AuthorController::class, 'restore']);
        route::delete('/{id}/force', [AuthorController::class, 'forceDelete']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::get('/AllOnlyTrashed', [CategoryController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [CategoryController::class, 'show'])->middleware('permission:library.catalog.view');
        Route::post('/', [CategoryController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::post('{id}', [CategoryController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{id}', [CategoryController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::post('/{id}/restore', [CategoryController::class, 'restore']);
        route::delete('/{id}/force', [CategoryController::class, 'forceDelete']);
    });

    Route::prefix('Publishers')->group(function () {
        Route::get('/', [PublishersController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::get('/AllOnlyTrashed', [PublishersController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [PublishersController::class, 'show'])->middleware('permission:library.catalog.view');
        Route::post('/', [PublishersController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::post('{id}', [PublishersController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{id}', [PublishersController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::post('/{id}/restore', [PublishersController::class, 'restore']);
        route::delete('/{id}/force', [PublishersController::class, 'forceDelete']);
    });

    Route::prefix('members')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::get('/AllOnlyTrashed', [MemberController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [MemberController::class, 'show'])->middleware('permission:library.catalog.view');
        Route::post('/', [MemberController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::post('{id}', [MemberController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{id}', [MemberController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::post('/{id}/restore', [MemberController::class, 'restore']);
        route::delete('/{id}/force', [MemberController::class, 'forceDelete']);
    });

    Route::prefix('books')->group(function () {
        Route::get('{book}/copies', [BookCopyController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::post('{book}/copies', [BookCopyController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::put('{book}/copies/{copy}', [BookCopyController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{book}/copies/{copy}', [BookCopyController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::get('/', [BookController::class, 'index'])->middleware('permission:library.catalog.view');
        Route::get('/AllOnlyTrashed', [BookController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [BookController::class, 'show'])->middleware('permission:library.catalog.view');
        Route::post('/', [BookController::class, 'store'])->middleware('permission:library.catalog.manage');
        Route::post('/Update/{id}', [BookController::class, 'update'])->middleware('permission:library.catalog.manage');
        Route::delete('{id}', [BookController::class, 'destroy'])->middleware('permission:library.catalog.manage');
        Route::post('/{id}/restore', [BookController::class, 'restore']);
        route::delete('/{id}/force', [BookController::class, 'forceDelete']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->middleware('permission:library.circulation.view');
        Route::get('/AllOnlyTrashed', [TransactionController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [TransactionController::class, 'show'])->middleware('permission:library.circulation.view');
        Route::post('/', [TransactionController::class, 'store'])->middleware('permission:library.circulation.manage');
        Route::post('{id}', [TransactionController::class, 'update'])->middleware('permission:library.circulation.manage');
        Route::post('{id}/renew', [TransactionController::class, 'renew'])->middleware('permission:library.circulation.manage');
        Route::delete('{id}', [TransactionController::class, 'destroy'])->middleware('permission:library.circulation.manage');
        Route::post('/{id}/restore', [TransactionController::class, 'restore'])->middleware('permission:library.circulation.manage');
        route::delete('/{id}/force', [TransactionController::class, 'forceDelete'])->middleware('permission:library.circulation.manage');
    });

    Route::prefix('fines')->group(function () {
        Route::get('/', [FineController::class, 'index'])->middleware('permission:library.fines.view');
        Route::get('{fine}', [FineController::class, 'show'])->middleware('permission:library.fines.view');
        Route::patch('{fine}', [FineController::class, 'update'])->middleware('permission:library.fines.manage');
    });

    Route::prefix('reservations')->middleware('permission:library.circulation.manage')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::post('/', [ReservationController::class, 'store']);
        Route::post('{reservation}/cancel', [ReservationController::class, 'cancel']);
    });

    Route::get('reports/circulation', [LibraryReportController::class, 'circulation'])
        ->middleware('permission:library.circulation.view');
});
