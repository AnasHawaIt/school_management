<?php

namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Book;
use Modules\Library\Filters\BookFilter;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getBookOnlyTrashed()
    {
        return Book::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);

        $book->restore();

        return $book;
    }

    public function forceDelete($id)
    {
        $book = Book::withTrashed()->findOrFail($id);

        $book->forceDelete();

        return $book;
    }

    public function query()
    {
        return Book::query();
    }

    /**
     * Check whether the book has at least one available
     * physical copy.
     */
    public function isAvailable(int $bookId): bool
    {
        return $this->availableCopiesCount($bookId) > 0;
    }

    /**
     * Count available physical copies.
     *
     * Physical copies are the source of truth.
     * There is no books.copies column.
     */
    public function availableCopiesCount(int $bookId): int
    {
        return Book::query()
            ->findOrFail($bookId)
            ->copies()
            ->where('status', BookCopiesStatus::AVAILABLE)
            ->count();
    }

    public function getAll($request)
    {
        $query = $this->query();

        $query = (new BookFilter($request))->apply($query);

        $query->with([
            'author',
            'category',
        ]);

        if (!$request->filled('sort')) {
            $query->latest();
        }

        return $query->paginate(
            $request->get('per_page', 10)
        );
    }

    public function find($id)
    {
        return Book::with([
            'author',
            'category',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update($id, array $data)
    {
        $book = $this->find($id);

        $book->update($data);

        return $book;
    }

    public function delete($id)
    {
        $book = $this->find($id);

        return $book->delete();
    }
}
