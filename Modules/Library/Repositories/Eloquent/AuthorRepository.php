<?php

namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Author;
use Modules\Library\Filters\AuthorFilter;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAuthorOnlyTrashed()
    {
        $query = Author::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $bus = Author::withTrashed()->findOrFail($id);
        return $bus->restore();
    }

    public function forceDelete($id)
    {
        $bus = Author::withTrashed()->findOrFail($id);
        return $bus->forceDelete();
    }

    public function getAll($request)
    {
        $query = Author::query();

        $query = (new AuthorFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));

    }

    public function find($id)
    {
        return Author::findOrFail($id);
    }

    public function create(array $data)
    {
        return Author::create($data);
    }

    public function update($id, array $data)
    {
        $author = $this->find($id);
        $author->update($data);
        return $author;
    }

    public function delete($id)
    {
        $author = $this->find($id);
        return $author->delete();
    }
}
