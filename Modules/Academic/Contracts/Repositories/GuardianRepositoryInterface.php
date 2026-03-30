<?php

namespace Modules\Academic\Contracts\Repositories;

interface GuardianRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function attachStudent(int $guardianId, int $studentId, array $pivotData): bool;
    public function detachStudent(int $guardianId, int $studentId): bool;
    public function getWithStudents(int $id);
}
