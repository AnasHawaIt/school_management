<?php

namespace Modules\Transport\Repositories\Eloquent;

use Modules\Transport\Entities\Route;
use Modules\Transport\Filters\RouteFilter;
use Modules\Transport\Repositories\Interfaces\RouteRepositoryInterface;

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
}
