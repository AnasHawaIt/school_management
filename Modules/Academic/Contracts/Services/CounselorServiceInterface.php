<?php

namespace Modules\Academic\Contracts\Services;

interface CounselorServiceInterface
{
    public function getAll(array $filters = []);
    public function getCounselor(int $id);
    public function createCounselor(array $data): object;
    public function updateCounselor(int $id, array $data): object;
    public function deleteCounselor(int $id): bool;
    public function restoreCounselor(int $id): bool;
    public function assignSection(int $counselorId, int $sectionId, int $academicYearId): bool;
    public function unassignSection(int $counselorId, int $sectionId, int $academicYearId): bool;
    public function getCounselorSections(int $counselorId, int $academicYearId);
    public function toggleStatus(int $id): object;
}
