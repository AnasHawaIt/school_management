<?php

namespace Modules\Academic\Contracts\Services;

use Modules\Academic\Entities\Guardian;

interface GuardianServiceInterface
{
    public function getAllGuardians(array $filters = []);
    public function getGuardian(int $id);
    public function createGuardian(array $data): Guardian;
    public function updateGuardian(int $id, array $data): Guardian;
    public function deleteGuardian(int $id): bool;
    public function restoreGuardian(int $id): bool;
    public function attachStudent(int $guardianId, int $studentId, array $pivotData): bool;
    public function detachStudent(int $guardianId, int $studentId): bool;
    public function getGuardianWithStudents(int $id);
}
