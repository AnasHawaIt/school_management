<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\Http\Controllers\AuthorController;
use Modules\Library\Http\Controllers\BookController;
use Modules\Library\Http\Controllers\CategoryController;
use Modules\Library\Http\Controllers\LibraryController;
use Modules\Library\Http\Controllers\MemberController;
use Modules\Library\Http\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('libraries', LibraryController::class)->names('library');
});

Route::prefix('library')->group(function() {
// ---------------- Authors ----------------
    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::get('{id}', [AuthorController::class, 'show']);
        Route::post('/', [AuthorController::class, 'store']);
        Route::put('{id}', [AuthorController::class, 'update']);
        Route::delete('{id}', [AuthorController::class, 'destroy']);
    });

// ---------------- Categories ----------------
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('{id}', [CategoryController::class, 'update']);
        Route::delete('{id}', [CategoryController::class, 'destroy']);
    });

// ---------------- Members ----------------
    Route::prefix('members')->group(function () {
        Route::get('/', [MemberController::class, 'index']);
        Route::get('{id}', [MemberController::class, 'show']);
        Route::post('/', [MemberController::class, 'store']);
        Route::put('{id}', [MemberController::class, 'update']);
        Route::delete('{id}', [MemberController::class, 'destroy']);
    });

// ---------------- Books ----------------
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('{id}', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::put('{id}', [BookController::class, 'update']);
        Route::delete('{id}', [BookController::class, 'destroy']);
    });

// ---------------- Transactions ----------------
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::put('{id}', [TransactionController::class, 'update']);
        Route::delete('{id}', [TransactionController::class, 'destroy']);
    });
});
