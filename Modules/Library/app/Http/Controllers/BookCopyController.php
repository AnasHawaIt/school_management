<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\app\Http\Requests\StoreBookCopyRequest;
use Modules\Library\app\Http\Requests\UpdateBookCopyRequest;

class BookCopyController extends Controller
{
    public function index(Book $book)
    {
        return response()->json($book->copies()->latest()->paginate(20));
    }

    public function store(StoreBookCopyRequest $request, Book $book): JsonResponse
    {
        $copy = $book->copies()->create($request->validated());

        return response()->json($copy, 201);
    }

    public function update(UpdateBookCopyRequest $request, Book $book, BookCopy $copy): JsonResponse
    {
        abort_unless($copy->book_id === $book->id, 404);

        $copy->update($request->validated());

        return response()->json($copy->refresh());
    }

    public function destroy(Book $book, BookCopy $copy): JsonResponse
    {
        abort_unless($copy->book_id === $book->id, 404);

        if ($copy->status === 'borrowed') {
            return response()->json([
                'message' => 'Borrowed copies cannot be deleted.',
            ], 422);
        }

        $copy->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
