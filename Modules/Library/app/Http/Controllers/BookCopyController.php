<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\app\Http\Requests\StoreBookCopyRequest;
use Modules\Library\app\Http\Requests\UpdateBookCopyRequest;
use Modules\Library\Services\BookCopyService;

class BookCopyController extends Controller
{
    public function __construct(
        protected BookCopyService $service
    ) {
    }

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
            'message' => 'Deleted',
        ]);
    }
}
