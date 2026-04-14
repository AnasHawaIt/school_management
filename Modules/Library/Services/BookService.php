<?php

namespace Modules\Library\Services;

use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookService
{
    protected $repo;

    public function __construct(BookRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getBookOnlyTrashed()
    {
        return $this->repo->getBookOnlyTrashed();
    }

    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $bus = $this->repo->create($data);

        event(new BookCreated($bus, auth()->id()));

        return $bus;
    }

    public function update($id, array $data)
    {
        $book= $this->repo->update($id, $data);

        event(new BookUpdated($book, auth()->id()));

        return $book;
    }

    public function delete($id)
    {
        $book = $this->repo->find($id);

        if (!$book) {
            throw new \Exception('Bus not found');
        }

        $this->repo->delete($id);

        event(new BookDeleted($book, auth()->id()));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

}
