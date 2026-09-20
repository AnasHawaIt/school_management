<?php

namespace Modules\Library\app\Repositories\Eloquent;

use Modules\Library\app\Entities\Publisher;
use Modules\Library\app\Filters\PublisherFilter;
use Modules\Library\app\Repositories\Interfaces\PublishersRepositoryInterface;

class PublishersRepository implements PublishersRepositoryInterface
{
    public function getPublishersOnlyTrashed()
    {
        $query = Publisher::onlyTrashed()->get();

        return $query;
    }

    public function restore($id)
    {
        $publisher = Publisher::withTrashed()->findOrFail($id);

        $publisher->restore();

        return $publisher;
    }

    public function forceDelete($id)
    {
        $publisher = Publisher::withTrashed()->findOrFail($id);

        $publisher->forceDelete();

        return $publisher;
    }

    public function getAll($request)
    {
        $query = Publisher::query();

        $query = (new PublisherFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Publisher::findOrFail($id);
    }

    public function create(array $data)
    {
        return Publisher::create($data);
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
