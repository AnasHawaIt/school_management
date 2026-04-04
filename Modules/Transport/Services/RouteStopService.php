<?php


namespace Modules\Transport\Services;

use Illuminate\Support\Facades\DB;
use Modules\Transport\Events\RouteStopEvents\RouteStopCreated;
use Modules\Transport\Events\RouteStopEvents\RouteStopDeleted;
use Modules\Transport\Events\RouteStopEvents\RouteStopUpdated;
use Modules\Transport\Repositories\Interfaces\RouteStopRepositoryInterface;

class RouteStopService
{
    protected $repo;

    public function __construct(RouteStopRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getRouteStopsOnlyTrashed()
    {
        return $this->repo->getRouteStopsOnlyTrashed();
    }

    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    public function reorder($routeId)
    {
        return $this->repo->reorder($routeId);
    }

    public function getByRoute($routeId)
    {
        return $this->repo->getByRoute($routeId);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $created = $this->repo->create($data);

            $this->repo->reorder($data['route_id']);

            event(new RouteStopCreated($created),auth()->id());

            return $created;
        });
    }

    public function addMultipleStops($routeId, array $stops)
    {
        DB::beginTransaction();

        try {
            $data = [];

            foreach ($stops as $index => $stop) {
                $data[] = [
                    'route_id' => $routeId,
                    'stop_name' => $stop['stop_name'],
                    'sequence' => $index + 1,
                ];
            }

            $this->repo->insert($data);

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        $routeSto= $this->repo->update($id, $data);

        event(new RouteStopUpdated($routeSto),auth()->id());

        return $routeSto;
    }

    public function delete($id)
    {
        $routeStop = $this->repo->find($id);

        $routeId = $routeStop->route_id;

        if (!$routeStop) {
            throw new \Exception('Route not found');
        }

        $this->repo->delete($id);

        $this->repo->reorder($routeId);

        event(new RouteStopDeleted($routeStop),auth()->id());

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function getAll( $request)
    {
        return $this->repo->getAll($request);
    }
}
