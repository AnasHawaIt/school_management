<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Fine;
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
        $copy = $book->copies()->create($request->validated());

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
         * A borrowed physical copy cannot be manually changed
         * to available/maintenance.
         *
         * It must be released through the borrowing return workflow.
         */
        if (
            $copy->status === 'borrowed'
            && isset($data['status'])
            && $data['status'] !== 'borrowed'
            && ! in_array($data['status'], ['lost', 'damaged'], true)
        ) {
            return response()->json([
                'message' => 'Borrowed copies can only be released by returning the active loan.',
            ], 422);
        }

        $previousStatus = $copy->status;

        $copy->update($data);

        /*
         * If a borrowed copy is marked lost or damaged,
         * create/update the related fine.
         */
        if (
            isset($data['status'])
            && in_array($data['status'], ['lost', 'damaged'], true)
            && $previousStatus !== $data['status']
        ) {
            $transaction = $copy->transactions()
                ->whereIn('status', ['borrowed', 'late'])
                ->latest('id')
                ->first();

            if ($transaction) {
                $amount = $copy->replacement_cost
                    ?? config("library.{$data['status']}_copy_compensation");

                Fine::updateOrCreate(
                    ['transaction_id' => $transaction->id],
                    [
                        'amount' => $amount,
                        'status' => 'unpaid',
                        'notes' => "Copy marked {$data['status']}.",
                    ]
                );
            }
        }

        return response()->json($copy->refresh());
    }

    public function destroy(
        Book $book,
        BookCopy $copy
    ): JsonResponse {
        abort_unless($copy->book_id === $book->id, 404);

        if ($copy->status === 'borrowed') {
            return response()->json([
                'message' => 'Borrowed copies cannot be deleted.',
            ], 422);
        }

        $copy->delete();

        return response()->json([
            'message' => 'Deleted',
        ]);
    }
}
