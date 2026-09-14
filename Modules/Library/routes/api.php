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


/*
|--------------------------------------------------------------------------
| Library API
|--------------------------------------------------------------------------
|
| Base:
| /api/library
|
*/

Route::middleware(['auth:sanctum'])
    ->prefix('library')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Library
        |--------------------------------------------------------------------------
        */

        Route::prefix('libraries')->group(function () {

            Route::get('/', [
                LibraryController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            Route::get('/{id}', [
                LibraryController::class,
                'show'
            ])->middleware('permission:library.catalog.view');

            Route::post('/', [
                LibraryController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            Route::post('/{id}', [
                LibraryController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                LibraryController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::delete('/{id}', [
                LibraryController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');
        });


        /*
        |--------------------------------------------------------------------------
        | Authors
        |--------------------------------------------------------------------------
        */

        Route::prefix('authors')->group(function () {

            // List
            Route::get('/', [
                AuthorController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            // Trashed
            Route::get('/trashed', [
                AuthorController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.catalog.manage');

            // Create
            Route::post('/', [
                AuthorController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            // Restore
            Route::post('/{id}/restore', [
                AuthorController::class,
                'restore'
            ])->middleware('permission:library.catalog.restore');

            // Force Delete
            Route::delete('/{id}/force', [
                AuthorController::class,
                'forceDelete'
            ])->middleware('permission:library.catalog.force_delete');

            // Update
            Route::post('/{id}', [
                AuthorController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                AuthorController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            // Delete
            Route::delete('/{id}', [
                AuthorController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');

            // Show
            Route::get('/{id}', [
                AuthorController::class,
                'show'
            ])->middleware('permission:library.catalog.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::prefix('categories')->group(function () {

            // List
            Route::get('/', [
                CategoryController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            // Trashed
            Route::get('/trashed', [
                CategoryController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.catalog.manage');

            // Create
            Route::post('/', [
                CategoryController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            // Restore
            Route::post('/{id}/restore', [
                CategoryController::class,
                'restore'
            ])->middleware('permission:library.catalog.restore');

            // Force Delete
            Route::delete('/{id}/force', [
                CategoryController::class,
                'forceDelete'
            ])->middleware('permission:library.catalog.force_delete');

            // Update
            Route::post('/{id}', [
                CategoryController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                CategoryController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            // Delete
            Route::delete('/{id}', [
                CategoryController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');

            // Show
            Route::get('/{id}', [
                CategoryController::class,
                'show'
            ])->middleware('permission:library.catalog.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Publishers
        |--------------------------------------------------------------------------
        */

        Route::prefix('publishers')->group(function () {

            // List
            Route::get('/', [
                PublishersController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            // Trashed
            Route::get('/trashed', [
                PublishersController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.catalog.manage');

            // Create
            Route::post('/', [
                PublishersController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            // Restore
            Route::post('/{id}/restore', [
                PublishersController::class,
                'restore'
            ])->middleware('permission:library.catalog.restore');

            // Force Delete
            Route::delete('/{id}/force', [
                PublishersController::class,
                'forceDelete'
            ])->middleware('permission:library.catalog.force_delete');

            // Update
            Route::post('/{id}', [
                PublishersController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                PublishersController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            // Delete
            Route::delete('/{id}', [
                PublishersController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');

            // Show
            Route::get('/{id}', [
                PublishersController::class,
                'show'
            ])->middleware('permission:library.catalog.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Members
        |--------------------------------------------------------------------------
        */

        Route::prefix('members')->group(function () {

            // List
            Route::get('/', [
                MemberController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            // Trashed
            Route::get('/trashed', [
                MemberController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.catalog.manage');

            // Create
            Route::post('/', [
                MemberController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            // Restore
            Route::post('/{id}/restore', [
                MemberController::class,
                'restore'
            ])->middleware('permission:library.catalog.restore');

            // Force Delete
            Route::delete('/{id}/force', [
                MemberController::class,
                'forceDelete'
            ])->middleware('permission:library.catalog.force_delete');

            // Update
            Route::post('/{id}', [
                MemberController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                MemberController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            // Delete
            Route::delete('/{id}', [
                MemberController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');

            // Show
            Route::get('/{id}', [
                MemberController::class,
                'show'
            ])->middleware('permission:library.catalog.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Books
        |--------------------------------------------------------------------------
        */

        Route::prefix('books')->group(function () {

            // List
            Route::get('/', [
                BookController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            // Trashed
            Route::get('/trashed', [
                BookController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.catalog.manage');

            // Create
            Route::post('/', [
                BookController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');


            /*
            |--------------------------------------------------------------------------
            | Physical Copies
            |--------------------------------------------------------------------------
            */

            Route::get('/{book}/copies', [
                BookCopyController::class,
                'index'
            ])->middleware('permission:library.catalog.view');

            Route::post('/{book}/copies', [
                BookCopyController::class,
                'store'
            ])->middleware('permission:library.catalog.manage');

            Route::post('/{book}/copies/{copy}', [
                BookCopyController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{book}/copies/{copy}', [
                BookCopyController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::delete('/{book}/copies/{copy}', [
                BookCopyController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');


            /*
            |--------------------------------------------------------------------------
            | Book Restore / Force Delete
            |--------------------------------------------------------------------------
            */

            Route::post('/{id}/restore', [
                BookController::class,
                'restore'
            ])->middleware('permission:library.catalog.restore');

            Route::delete('/{id}/force', [
                BookController::class,
                'forceDelete'
            ])->middleware('permission:library.catalog.force_delete');


            /*
            |--------------------------------------------------------------------------
            | Book Update
            |--------------------------------------------------------------------------
            */

            Route::post('/{id}', [
                BookController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');

            Route::patch('/{id}', [
                BookController::class,
                'update'
            ])->middleware('permission:library.catalog.manage');


            /*
            |--------------------------------------------------------------------------
            | Book Delete
            |--------------------------------------------------------------------------
            */

            Route::delete('/{id}', [
                BookController::class,
                'destroy'
            ])->middleware('permission:library.catalog.delete');


            /*
            |--------------------------------------------------------------------------
            | Book Show
            |--------------------------------------------------------------------------
            */

            Route::get('/{id}', [
                BookController::class,
                'show'
            ])->middleware('permission:library.catalog.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Transactions / Borrowings
        |--------------------------------------------------------------------------
        */

        Route::prefix('transactions')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | List
            |--------------------------------------------------------------------------
            */

            Route::get('/', [
                TransactionController::class,
                'index'
            ])->middleware('permission:library.circulation.view');


            /*
            |--------------------------------------------------------------------------
            | Trashed
            |--------------------------------------------------------------------------
            */

            Route::get('/trashed', [
                TransactionController::class,
                'trashed'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Create Borrowing
            |--------------------------------------------------------------------------
            */

            Route::post('/', [
                TransactionController::class,
                'store'
            ])->middleware('permission:library.circulation.manage');

            Route::get('/status-dashboard', [
                TransactionController::class,
                'statusDashboard'
            ])->middleware('permission:library.circulation.manage');
            /*
            |--------------------------------------------------------------------------
            | Workflow
            |--------------------------------------------------------------------------
            */

            Route::post('/{id}/approve', [
                TransactionController::class,
                'approve'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/reject', [
                TransactionController::class,
                'reject'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/pickup', [
                TransactionController::class,
                'pickup'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/cancel', [
                TransactionController::class,
                'cancel'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/return', [
                TransactionController::class,
                'returnBook'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/renew', [
                TransactionController::class,
                'renew'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/lost', [
                TransactionController::class,
                'markLost'
            ])->middleware('permission:library.circulation.manage');

            Route::post('/{id}/overdue', [
                TransactionController::class,
                'markOverdue'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Restore
            |--------------------------------------------------------------------------
            */

            Route::post('/{id}/restore', [
                TransactionController::class,
                'restore'
            ])->middleware('permission:library.circulation.restore');


            /*
            |--------------------------------------------------------------------------
            | Force Delete
            |--------------------------------------------------------------------------
            */

            Route::delete('/{id}/force', [
                TransactionController::class,
                'forceDelete'
            ])->middleware('permission:library.circulation.force_delete');


            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            Route::post('/{id}', [
                TransactionController::class,
                'update'
            ])->middleware('permission:library.circulation.manage');

            Route::patch('/{id}', [
                TransactionController::class,
                'update'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            Route::delete('/{id}', [
                TransactionController::class,
                'destroy'
            ])->middleware('permission:library.circulation.delete');


            /*
            |--------------------------------------------------------------------------
            | Show
            |--------------------------------------------------------------------------
            */

            Route::get('/{id}', [
                TransactionController::class,
                'show'
            ])->middleware('permission:library.circulation.view');
        });


        /*
        |--------------------------------------------------------------------------
        | Fines
        |--------------------------------------------------------------------------
        */

        Route::prefix('fines')->group(function () {

            // List
            Route::get('/', [
                FineController::class,
                'index'
            ])->middleware('permission:library.fines.view');

            // Show
            Route::get('/{fine}', [
                FineController::class,
                'show'
            ])->middleware('permission:library.fines.view');

            // Pay
            Route::post('/{fine}/pay', [
                FineController::class,
                'pay'
            ])->middleware('permission:library.fines.manage');

            // Waive
            Route::post('/{fine}/waive', [
                FineController::class,
                'waive'
            ])->middleware('permission:library.fines.manage');
        });


        /*
        |--------------------------------------------------------------------------
        | Reservations
        |--------------------------------------------------------------------------
        */

        Route::prefix('reservations')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | List / Show
            |--------------------------------------------------------------------------
            */

            Route::get('/', [
                ReservationController::class,
                'index'
            ])->middleware('permission:library.circulation.view');


            Route::get('/{reservation}', [
                ReservationController::class,
                'show'
            ])->middleware('permission:library.circulation.view');



            Route::get('/trashed', [
                ReservationController::class,
                'AllOnlyTrashed'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            Route::post('/', [
                ReservationController::class,
                'store'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Reservations By Book
            |--------------------------------------------------------------------------
            */

            Route::get('/books/{bookId}', [
                ReservationController::class,
                'byBook'
            ])->middleware('permission:library.circulation.view');


            /*
            |--------------------------------------------------------------------------
            | Reservations By Member
            |--------------------------------------------------------------------------
            */

            Route::get('/members/{memberId}', [
                ReservationController::class,
                'byMember'
            ])->middleware('permission:library.circulation.view');


            /*
            |--------------------------------------------------------------------------
            | Member Pending Reservation
            |--------------------------------------------------------------------------
            */

            Route::get('/books/{bookId}/members/{memberId}/pending', [
                ReservationController::class,
                'memberPending'
            ])->middleware('permission:library.circulation.view');


            /*
            |--------------------------------------------------------------------------
            | Cancel
            |--------------------------------------------------------------------------
            */

            Route::post('/{reservation}/cancel', [
                ReservationController::class,
                'cancel'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Fulfill
            |--------------------------------------------------------------------------
            */

            Route::post('/{reservation}/fulfill', [
                ReservationController::class,
                'fulfill'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Expire
            |--------------------------------------------------------------------------
            */

            Route::post('/{reservation}/expire', [
                ReservationController::class,
                'expire'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Notify Next Reservation
            |--------------------------------------------------------------------------
            */

            Route::post('/notifyNext/{bookId}', [
                ReservationController::class,
                'notifyNext'
            ])->middleware('permission:library.circulation.manage');
            /*
            |--------------------------------------------------------------------------
            | Process Next Reservation
            |--------------------------------------------------------------------------
            */

            Route::post('/books/{bookId}/process-next', [
                ReservationController::class,
                'processNext'
            ])->middleware('permission:library.circulation.manage');


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            Route::delete('/{reservation}', [
                ReservationController::class,
                'destroy'
            ])->middleware('permission:library.circulation.manage');


            // Restore
            Route::post('/{id}/restore', [
                ReservationController::class,
                'restore'
            ])->middleware('permission:library.circulation.restore');


            // Force Delete
            Route::delete('/{id}/force', [
                ReservationController::class,
                'forceDelete'
            ])->middleware('permission:library.circulation.force_delete');

        });


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::prefix('reports')->group(function () {

            Route::get('/circulation', [
                LibraryReportController::class,
                'circulation'
            ])->middleware('permission:library.circulation.view');
        });
    });

