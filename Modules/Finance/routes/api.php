<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FeeTypeController;
use Modules\Finance\Http\Controllers\FeeStructureController;
use Modules\Finance\Http\Controllers\DiscountController;
use Modules\Finance\Http\Controllers\StudentFeeController;
use Modules\Finance\Http\Controllers\PaymentController;
use Modules\Finance\Http\Controllers\InvoiceController;
use Modules\Finance\Http\Controllers\FinanceReportController;

/*
|--------------------------------------------------------------------------
| Finance Module API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {


    Route::prefix('finance/fee-types')->group(function () {
        Route::get('/',        [FeeTypeController::class, 'index']);
        Route::post('/',       [FeeTypeController::class, 'store']);
        Route::get('/{id}',    [FeeTypeController::class, 'show']);
        Route::put('/{id}',    [FeeTypeController::class, 'update']);
        Route::delete('/{id}', [FeeTypeController::class, 'destroy']);
    });


    Route::prefix('finance/fee-structures')->group(function () {
        Route::get('/',        [FeeStructureController::class, 'index']);
        Route::post('/',       [FeeStructureController::class, 'store']);
        Route::get('/{id}',    [FeeStructureController::class, 'show']);
        Route::put('/{id}',    [FeeStructureController::class, 'update']);
        Route::delete('/{id}', [FeeStructureController::class, 'destroy']);
    });


    Route::prefix('finance/discounts')->group(function () {
        Route::get('/',        [DiscountController::class, 'index']);
        Route::post('/',       [DiscountController::class, 'store']);
        Route::get('/{id}',    [DiscountController::class, 'show']);
        Route::put('/{id}',    [DiscountController::class, 'update']);
        Route::delete('/{id}', [DiscountController::class, 'destroy']);
    });

    Route::prefix('finance/student-fees')->group(function () {
        Route::get('/',                       [StudentFeeController::class, 'index']);
        Route::post('/',                      [StudentFeeController::class, 'store']);
        Route::post('/assign-year',           [StudentFeeController::class, 'assignYearFees']);
        Route::get('/overdue',                [StudentFeeController::class, 'overdue']);
        Route::get('/summary',                [StudentFeeController::class, 'summary']);
        Route::get('/{id}',                   [StudentFeeController::class, 'show']);
        Route::post('/{id}/waive',            [StudentFeeController::class, 'waive']);
        Route::get('/student/{studentId}',    [StudentFeeController::class, 'byStudent']);
    });

    Route::prefix('finance/payments')->group(function () {
        Route::get('/',                      [PaymentController::class, 'index']);
        Route::post('/',                     [PaymentController::class, 'store']);
        Route::get('/report',                [PaymentController::class, 'report']);
        Route::get('/{id}',                  [PaymentController::class, 'show']);
        Route::post('/{id}/refund',          [PaymentController::class, 'refund']);
        Route::get('/student/{studentId}',   [PaymentController::class, 'byStudent']);
    });

    Route::prefix('finance/invoices')->group(function () {
        Route::get('/',                    [InvoiceController::class, 'index']);
        Route::post('/generate',           [InvoiceController::class, 'generate']);
        Route::get('/overdue',             [InvoiceController::class, 'overdue']);
        Route::get('/student/{studentId}', [InvoiceController::class, 'byStudent']);
        Route::post('/{id}/send',          [InvoiceController::class, 'send'])->whereNumber('id');
        Route::post('/{id}/cancel',        [InvoiceController::class, 'cancel'])->whereNumber('id');
        Route::get('/{id}',                [InvoiceController::class, 'show'])->whereNumber('id');
    });


    Route::prefix('finance/reports')->group(function () {
        Route::get('/dashboard',    [FinanceReportController::class, 'dashboard']);
        Route::get('/collection',   [FinanceReportController::class, 'collection']);
        Route::get('/outstanding',  [FinanceReportController::class, 'outstanding']);
    });
});
