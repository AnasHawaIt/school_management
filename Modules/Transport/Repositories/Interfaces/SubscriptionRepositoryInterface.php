<?php

namespace Modules\Transport\Repositories\Interfaces;

interface SubscriptionRepositoryInterface
{
    public function getAll($request);
    public function create(array $data);
    public function find($id);
    public function getByStudent($studentId);
    public function countActiveByRoute($routeId);
    public function getActiveByRoute($routeId);
    public function update($id, array $data);
    public function delete($id);
}
