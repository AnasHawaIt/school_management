<?php

namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Publishers;
use Modules\Library\Filters\CategoryFilter;
use Modules\Library\Repositories\Interfaces\PublishersRepositoryInterface;

class PublishersRepository implements PublishersRepositoryInterface
{
    public function getPublishersOnlyTrashed()
    {
        $query = Publishers::onlyTrashed()->get();

        return $query;
    }

    public function restore($id)
    {
        $publisher = Publishers::withTrashed()->findOrFail($id);

        $publisher->restore();

        return $publisher;
    }

    public function forceDelete($id)
    {
        $publisher = Publishers::withTrashed()->findOrFail($id);

        $publisher->forceDelete();

        return $publisher;
    }

    public function getAll($request)
    {
        $query = Publishers::query();

        $query = (new CategoryFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Publishers::findOrFail($id);
    }

    public function create(array $data)
    {
        return Publishers::create($data);
    }

    public function update($id, array $data)
    {
        $publisher = $this->findById($id);
        $publisher->update($data);
        return $publisher;
    }

    public function delete($id)
    {
        $publisher = $this->findById($id);
        return $publisher->delete();
    }
}
