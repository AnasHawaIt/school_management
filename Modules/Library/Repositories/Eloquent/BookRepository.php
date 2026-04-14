<?php
namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Book;
use Modules\Library\Filters\BookFilter;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getBookOnlyTrashed()
    {
        $query = Book::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $bus = Book::withTrashed()->findOrFail($id);
        return $bus->restore();
    }

    public function forceDelete($id)
    {
        $bus = Book::withTrashed()->findOrFail($id);
        return $bus->forceDelete();
    }

    public function query()
    {
        return Book::query();
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
        return Book::findOrFail($id);
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
