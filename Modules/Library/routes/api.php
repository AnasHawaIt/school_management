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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('libraries', LibraryController::class)->names('library');
});

Route::middleware(['auth:sanctum'])->prefix('library')->group(function() {
    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::get('/AllOnlyTrashed', [AuthorController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [AuthorController::class, 'show']);
        Route::post('/', [AuthorController::class, 'store']);
        Route::post('/Update/{id}', [AuthorController::class, 'update']);
        Route::delete('{id}', [AuthorController::class, 'destroy']);
        Route::post('/{id}/restore', [AuthorController::class, 'restore']);
        route::delete('/{id}/force', [AuthorController::class, 'forceDelete']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/AllOnlyTrashed', [CategoryController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::post('{id}', [CategoryController::class, 'update']);
        Route::delete('{id}', [CategoryController::class, 'destroy']);
        Route::post('/{id}/restore', [CategoryController::class, 'restore']);
        route::delete('/{id}/force', [CategoryController::class, 'forceDelete']);
    });

    Route::prefix('Publishers')->group(function () {
        Route::get('/', [PublishersController::class, 'index']);
        Route::get('/AllOnlyTrashed', [PublishersController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [PublishersController::class, 'show']);
        Route::post('/', [PublishersController::class, 'store']);
        Route::post('{id}', [PublishersController::class, 'update']);
        Route::delete('{id}', [PublishersController::class, 'destroy']);
        Route::post('/{id}/restore', [PublishersController::class, 'restore']);
        route::delete('/{id}/force', [PublishersController::class, 'forceDelete']);
    });

    Route::prefix('members')->group(function () {
        Route::get('/', [MemberController::class, 'index']);
        Route::get('/AllOnlyTrashed', [MemberController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [MemberController::class, 'show']);
        Route::post('/', [MemberController::class, 'store']);
        Route::post('{id}', [MemberController::class, 'update']);
        Route::delete('{id}', [MemberController::class, 'destroy']);
        Route::post('/{id}/restore', [MemberController::class, 'restore']);
        route::delete('/{id}/force', [MemberController::class, 'forceDelete']);
    });

    Route::prefix('books')->group(function () {
        Route::get('{book}/copies', [BookCopyController::class, 'index']);
        Route::post('{book}/copies', [BookCopyController::class, 'store']);
        Route::put('{book}/copies/{copy}', [BookCopyController::class, 'update']);
        Route::delete('{book}/copies/{copy}', [BookCopyController::class, 'destroy']);
        Route::get('/', [BookController::class, 'index']);
        Route::get('/AllOnlyTrashed', [BookController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::post('/Update/{id}', [BookController::class, 'update']);
        Route::delete('{id}', [BookController::class, 'destroy']);
        Route::post('/{id}/restore', [BookController::class, 'restore']);
        route::delete('/{id}/force', [BookController::class, 'forceDelete']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/AllOnlyTrashed', [TransactionController::class, 'AllOnlyTrashed']);
        Route::get('{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::post('{id}', [TransactionController::class, 'update']);
        Route::delete('{id}', [TransactionController::class, 'destroy']);
        Route::post('/{id}/restore', [TransactionController::class, 'restore']);
        route::delete('/{id}/force', [TransactionController::class, 'forceDelete']);
    });

    Route::prefix('fines')->group(function () {
        Route::get('/', [FineController::class, 'index']);
        Route::get('{fine}', [FineController::class, 'show']);
        Route::patch('{fine}', [FineController::class, 'update']);
    });
});
