<?php

namespace Modules\Transport\Repositories\Interfaces;

interface RouteStopRepositoryInterface
{
    public function getByRoute($routeId);

    public function insert(array $data);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function reorder($routeId);

    public function delete($id);
}
