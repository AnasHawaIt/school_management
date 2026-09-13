<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Fine;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\app\Http\Requests\StoreBookCopyRequest;
use Modules\Library\app\Http\Requests\UpdateBookCopyRequest;

class BookCopyController extends Controller
{
    public function index(Book $book)
    {
        return response()->json(
            $book->copies()
                ->latest()
                ->paginate(20)
        );
    }

    public function store(
        StoreBookCopyRequest $request,
        Book $book
    ): JsonResponse {
        $data = $request->validated();

        /*
         * A new physical copy must always start as AVAILABLE.
         */
        $data['status'] = BookCopiesStatus::AVAILABLE;

        $copy = $book->copies()->create($data);

        return response()->json($copy, 201);
    }

    public function update(
        UpdateBookCopyRequest $request,
        Book $book,
        BookCopy $copy
    ): JsonResponse {
        abort_unless($copy->book_id === $book->id, 404);

        $data = $request->validated();

        /*
         * Reserved copies are controlled by the borrowing lifecycle.
         *
         * They must NOT be manually changed from the copy endpoint.
         */
        if ($copy->status === BookCopiesStatus::RESERVED) {

            if (
                isset($data['status'])
                && $data['status'] !== BookCopiesStatus::RESERVED->value
                && $data['status'] !== BookCopiesStatus::RESERVED
            ) {
                return response()->json([
                    'message' => 'Reserved copies can only be released through the borrowing cancellation or pickup workflow.',
                ], 422);
            }

            /*
             * Do not allow manually changing reserved copy.
             */
            unset($data['status']);
        }

        /*
         * Borrowed copies cannot be manually released.
         */
        if (
            $copy->status === BookCopiesStatus::BORROWED
            && isset($data['status'])
            && $data['status'] !== BookCopiesStatus::BORROWED->value
            && $data['status'] !== BookCopiesStatus::BORROWED
            && !in_array(
                $data['status'],
                [
                    BookCopiesStatus::LOST->value,
                    BookCopiesStatus::DAMAGED->value,
                ],
                true
            )
        ) {
            return response()->json([
                'message' => 'Borrowed copies can only be released by returning the active loan.',
            ], 422);
        }

        $previousStatus = $copy->status;

        $copy->update($data);

        /*
         * Create/update fine if an active borrowed copy
         * becomes lost or damaged.
         */
        if (
            isset($data['status'])
            && in_array($data['status'], [
                BookCopiesStatus::LOST->value,
                BookCopiesStatus::DAMAGED->value,
            ], true)
            && $previousStatus !== $data['status']
        ) {
            $transaction = $copy->transactions()
                ->whereIn('status', [
                    'borrowed',
                    'late',
                ])
                ->latest('id')
                ->first();

            if ($transaction) {

                $status = $data['status'];

                $amount = $copy->replacement_cost
                    ?? config("library.{$status}_copy_compensation");

                Fine::updateOrCreate(
                    [
                        'transaction_id' => $transaction->id,
                    ],
                    [
                        'amount' => $amount,
                        'status' => 'unpaid',
                        'notes' => "Copy marked {$status}.",
                    ]
                );
            }
        }

        return response()->json(
            $copy->refresh()
        );
    }

    public function destroy(
        Book $book,
        BookCopy $copy
    ): JsonResponse {
        abort_unless($copy->book_id === $book->id, 404);

        if (in_array($copy->status, [
            BookCopiesStatus::BORROWED,
            BookCopiesStatus::RESERVED,
        ], true)) {
            return response()->json([
                'message' => 'Borrowed or reserved copies cannot be deleted.',
            ], 422);
        }

        $copy->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }
}
