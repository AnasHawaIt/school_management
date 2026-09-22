<?php

namespace Modules\Attendance\app\Contracts\Repositories;

use Modules\Attendance\Entities\LeaveRequest;

interface LeaveRequestRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): LeaveRequest;
    public function update(int $id, array $data): LeaveRequest;
    public function delete(int $id): bool;
    public function approve(int $id, int $reviewerId, ?string $notes = null): LeaveRequest;
    public function reject(int $id, int $reviewerId, ?string $notes = null): LeaveRequest;
    public function getPending();
    public function getByRequestable(string $type, int $id);
}
