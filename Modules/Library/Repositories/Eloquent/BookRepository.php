<?php
namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Book;
use Modules\Library\Filters\BookFilter;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{

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

    public function findById($id)
    {
        return Book::findOrFail($id);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update($id, array $data)
    {
        $book = $this->findById($id);
        $book->update($data);
        return $book;
    }

    public function delete($id)
    {
        $book = $this->findById($id);
        return $book->delete();
    }

}
