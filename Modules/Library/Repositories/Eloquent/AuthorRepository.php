<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Library\Entities\Author;
use Modules\Library\Filters\AuthorFilter;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAuthorOnlyTrashed()
    {
        return Author::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $author = Author::withTrashed()->findOrFail($id);

        $author->restore();

        return $author;
    }

    public function forceDelete($id)
    {
        $author = Author::withTrashed()->findOrFail($id);

        $author->forceDelete();

        return $author;
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

        $author= Author::query()->update($data);

        return $author;
    }

    public function delete($id)
    {
        $author = $this->find($id);
        return $author->delete();
    }
}
