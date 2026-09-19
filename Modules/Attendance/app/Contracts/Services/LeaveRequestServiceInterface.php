<?php

namespace Modules\Attendance\app\Contracts\Services;

interface LeaveRequestServiceInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function createRequest(array $data): object;
    public function updateRequest(int $id, array $data): object;
    public function deleteRequest(int $id): bool;
    public function approve(int $id, int $reviewerId, ?string $notes = null): object;
    public function reject(int $id, int $reviewerId, ?string $notes = null): object;
    public function getPending();
}
