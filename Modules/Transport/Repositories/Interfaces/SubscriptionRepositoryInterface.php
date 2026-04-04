<?php

namespace Modules\Transport\Repositories\Interfaces;

interface SubscriptionRepositoryInterface
{
    public function getSubscriptionOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll($request);
    public function create(array $data);
    public function find($id);
    public function getByStudent($studentId);
    public function countActiveByRoute($routeId);
    public function getActiveByRoute($routeId);
    public function update($id, array $data);
    public function delete($id);
}
