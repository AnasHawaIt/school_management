<?php

namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Author;
use Modules\Library\Filters\AuthorFilter;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAll($request)
    {
        $query = Author::query();

        $query = (new AuthorFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));

    }

    public function findById($id)
    {
        return Author::findOrFail($id);
    }

    public function create(array $data)
    {
        return Author::create($data);
    }

    public function update($id, array $data)
    {
        $author = $this->findById($id);
        $author->update($data);
        return $author;
    }

    public function delete($id)
    {
        $author = $this->findById($id);
        return $author->delete();
    }
}
