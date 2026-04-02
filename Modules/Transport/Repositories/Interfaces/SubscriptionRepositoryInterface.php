<?php

namespace Modules\Transport\Repositories\Interfaces;

interface SubscriptionRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function getByStudent($studentId);
    public function update($id, array $data);
}
