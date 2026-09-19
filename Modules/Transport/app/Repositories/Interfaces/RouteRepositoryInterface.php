<?php

namespace Modules\Transport\app\Repositories\Interfaces;

interface RouteRepositoryInterface
{
    public function getRoutesOnlyTrashed();

    public function restore($id);

    public function forceDelete($id);

    public function getAll($request);

    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);
}
