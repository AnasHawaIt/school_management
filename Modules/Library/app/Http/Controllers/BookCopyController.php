<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Library\app\Entities\Book;
use Modules\Library\app\Entities\BookCopy;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\app\Http\Requests\StoreBookCopyRequest;
use Modules\Library\app\Http\Requests\UpdateBookCopyRequest;
use Modules\Library\app\Services\BookCopyService;

class BookCopyController extends Controller
{
    public function __construct(
        protected BookCopyService $service
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Book $book
    ): JsonResponse {
        return response()->json(
            $this->service->paginate(
                $book,
                $request
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreBookCopyRequest $request,
        Book $book
    ): JsonResponse {
        $copy = $this->service->create(
            $book,
            $request->validated()
        );

        return response()->json(
            $copy,
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        BookCopy $copy
    ): JsonResponse {
        return response()->json(
            $copy->load('book')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateBookCopyRequest $request,
        Book $book,
        BookCopy $copy
    ): JsonResponse {
        abort_unless(
            $copy->book_id === $book->id,
            404
        );

        $copy = $this->service->update(
            $copy,
            $request->validated()
        );

        return response()->json($copy);
    }

    /*
    |--------------------------------------------------------------------------
    | Change Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        BookCopy $copy
    ): JsonResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:available,reserved,borrowed,lost,damaged,maintenance',
            ],
        ]);

        $status = BookCopiesStatus::from(
            $validated['status']
        );

        $copy = $this->service->changeStatus(
            $copy,
            $status
        );

        return response()->json($copy);
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Book $book,
        BookCopy $copy
    ): JsonResponse {
        abort_unless(
            $copy->book_id === $book->id,
            404
        );

        $this->service->delete($copy);

        return response()->json([
            'message' => 'Book copy deleted successfully.',
        ]);
    }
}
