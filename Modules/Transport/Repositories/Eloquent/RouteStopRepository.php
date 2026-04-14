<?php

namespace Modules\Transport\Repositories\Eloquent;

use Modules\Transport\Entities\RouteStop;
use Modules\Transport\Filters\RouteStopFilter;
use Modules\Transport\Repositories\Interfaces\RouteStopRepositoryInterface;

class RouteStopRepository implements RouteStopRepositoryInterface
{
    public function getAll($request)
    {
        $query = RouteStop::query();

        $query = (new RouteStopFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function create(array $data)
    {
        return RouteStop::create($data);
    }

    public function insert(array $data)
    {
        return RouteStop::insert($data);
    }

    public function find($id)
    {
        return RouteStop::findOrFail($id);
    }

    public function getByRoute($routeId)
    {
        return RouteStop::where('route_id', $routeId)
            ->orderBy('sequence')
            ->get();
    }

    public function update($id, array $data)
    {
        $routeStop = $this->find($id);
        $routeStop->update($data);

        return $routeStop;
    }

    public function delete($id)
    {
        $routeStop = $this->find($id);
        return $routeStop->delete();
    }

    public function reorder($routeId)
    {
        $stops = RouteStop::where('route_id', $routeId)
            ->orderBy('sequence')
            ->get();

        foreach ($stops as $index => $stop) {
            $stop->update([
                'sequence' => $index + 1
            ]);
        }

        return true;
    }

    public function getRouteStopsOnlyTrashed()
    {
        $query = RouteStop::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $bus = RouteStop::withTrashed()->findOrFail($id);
        return $bus->restore();
    }

    public function forceDelete($id)
    {
        $bus = RouteStop::withTrashed()->findOrFail($id);
        return $bus->forceDelete();
    }
}
