<?php

namespace Modules\Attendance\Contracts\Repositories;

interface LeaveRequestRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function approve(int $id, int $reviewerId, ?string $notes = null): object;
    public function reject(int $id, int $reviewerId, ?string $notes = null): object;
    public function getPending();
    public function getByRequestable(string $type, int $id);
}
