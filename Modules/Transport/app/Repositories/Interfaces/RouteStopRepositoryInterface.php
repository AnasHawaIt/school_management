<?php

namespace Modules\Transport\app\Repositories\Interfaces;

interface RouteStopRepositoryInterface
{
    public function getRouteStopsOnlyTrashed();

    public function restore($id);

    public function forceDelete($id);

    public function getAll($request);

    public function getByRoute($routeId);

    public function insert(array $data);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function reorder($routeId);

    public function delete($id);
}
