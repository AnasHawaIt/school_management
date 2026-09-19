<?php

namespace Modules\Transport\app\Repositories\Eloquent;

use Modules\Transport\app\Entities\Route;
use Modules\Transport\app\Filters\RouteFilter;
use Modules\Transport\app\Repositories\Interfaces\RouteRepositoryInterface;

class RouteRepository implements RouteRepositoryInterface
{
    public function getAll($request)
    {
        $query = Route::with(['bus', 'stops']);

        $query = (new RouteFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function create(array $data)
    {
        return Route::create($data);
    }

    public function find($id)
    {
        return Route::with(['bus', 'stops'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $route = $this->find($id);
        $route->update($data);

        return $route;
    }

    public function delete($id)
    {
        $route = $this->find($id);
        return $route->delete();
    }

    public function getRoutesOnlyTrashed()
    {
        $query = Route::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $bus = Route::withTrashed()->findOrFail($id);
        return $bus->restore();
    }

    public function forceDelete($id)
    {
        $bus = Route::withTrashed()->findOrFail($id);
        return $bus->forceDelete();
    }
}
