<?php

namespace Modules\Academic\app\Contracts\Services;

use Modules\Academic\app\Entities\InspectionProgram;

interface InspectionProgramServiceInterface
{
    public function getAll(array $filters = []);
    public function getProgram(int $id);
    public function createProgram(array $data): InspectionProgram;
    public function updateProgram(int $id, array $data): InspectionProgram;
    public function deleteProgram(int $id): bool;
    public function restoreProgram(int $id): bool;
    public function assignCounselor(int $programId, int $counselorId, string $role): bool;
    public function unassignCounselor(int $programId, int $counselorId): bool;
    public function submitObservation(int $programId, int $counselorId, array $data): bool;
    public function updateStatus(int $id, string $status): InspectionProgram;
    public function getSectionPrograms(int $sectionId, array $filters = []);
    public function getCounselorPrograms(int $counselorId, array $filters = []);
    public function setCurrent(int $id): bool;
    public function getCurrentCounselorProgram(int $counselorId);
}
