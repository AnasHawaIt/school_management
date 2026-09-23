<?php

namespace Modules\Attendance\app\Contracts\Services;

use Modules\Attendance\Entities\LeaveRequest;

interface LeaveRequestServiceInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function createRequest(array $data): LeaveRequest;
    public function updateRequest(int $id, array $data): LeaveRequest;
    public function deleteRequest(int $id): bool;
    public function approve(int $id, int $reviewerId, ?string $notes = null): LeaveRequest;
    public function reject(int $id, int $reviewerId, ?string $notes = null): LeaveRequest;
    public function getPending();
}
