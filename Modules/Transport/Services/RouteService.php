<?php

namespace Modules\Transport\Services;

use Modules\Transport\Repositories\Interfaces\RouteRepositoryInterface;

class RouteService
{
    protected $repo;
    protected $routeStopService;

    public function __construct(
        RouteRepositoryInterface $repo,
        RouteStopService $routeStopService
    ) {
        $this->repo = $repo;
        $this->routeStopService = $routeStopService;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function addStops($routeId, array $stops)
    {
        return $this->routeStopService->addMultipleStops($routeId, $stops);
    }
}
