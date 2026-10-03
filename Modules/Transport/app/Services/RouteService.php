<?php

namespace Modules\Transport\Services;

use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Events\RouteEvents\RouteDeleted;
use Modules\Transport\Events\RouteEvents\RouteRestored;
use Modules\Transport\Events\RouteEvents\RouteUpdated;
use Modules\Transport\Repositories\Interfaces\RouteRepositoryInterface;

class RouteService
{
    protected $repo;

    public function __construct(
        RouteRepositoryInterface $repo
    ) {
        $this->repo = $repo;
    }

    public function getRoutesOnlyTrashed()
    {
        return $this->repo->getRoutesOnlyTrashed();
    }

    public function restore($id)
    {
        $route= $this->repo->restore($id);

        event(new RouteRestored($route));

        return $route;
    }

    public function forceDelete($id)
    {
        $route= $this->repo->forceDelete($id);

        event(new RouteDeleted($route));

        return true;
    }
    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $route= $this->repo->create($data);

        event(new RouteCreated($route, auth()->id()));

        return $route;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($id, array $data)
    {
        $route= $this->repo->update($id, $data);

        event(new RouteUpdated($route, auth()->id()));

        return $route;
    }

    public function delete($id)
    {
        $route = $this->repo->find($id);

        if (!$route) {
            throw new \Exception('Route not found');
        }

        $this->repo->delete($id);

        event(new RouteDeleted($route),auth()->id());

        return true;
    }

}
