<?php
namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Filters\BookFilter;
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

    public function isAvailable(int $bookId): bool
    {
        return $this->availableCopiesCount($bookId) > 0;
    }

    public function availableCopiesCount(int $bookId): int
    {
        $book = Book::query()
            ->findOrFail($bookId);

        if ($book->copies()->exists()) {
            return $book->copies()
                ->where('status', 'available')
                ->count();
        }

        return (int) $book->copies;
    }

    public function getAll($request)
    {
        $query = $this->query();

        $query = (new BookFilter($request))->apply($query);

        $query->with(['author', 'category']);

        $query->latest();

        return $query->paginate(
            $request->get('per_page', 10)
        );
    }

    public function find($id)
    {
        return Book::with(['author', 'category'])->findOrFail($id);
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
