<?php

namespace Modules\Transport\app\Repositories\Eloquent;

use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Filters\BusFilter;
use Modules\Transport\app\Repositories\Interfaces\BusRepositoryInterface;

class BusRepository implements BusRepositoryInterface
{
    public function getAll($request)
    {
        $query = Bus::query();

        $query = (new BusFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function create(array $data)
    {
        return Bus::create($data);
    }

    public function find($id)
    {
        return Bus::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $bus = $this->find($id);
        $bus->update($data);

        return $bus;
    }

    public function delete($id)
    {
        $bus = $this->find($id);
        return $bus->delete();
    }

    public function getBusesOnlyTrashed()
    {
        $query = Bus::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $bus = Bus::withTrashed()->findOrFail($id);
        return $bus->restore();
    }

    public function forceDelete($id)
    {
        $bus = Bus::withTrashed()->findOrFail($id);
        return $bus->forceDelete();
    }

}
