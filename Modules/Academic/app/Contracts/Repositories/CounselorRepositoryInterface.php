<?php

namespace Modules\Academic\app\Contracts\Repositories;

use App\Entities\Counselor;

interface CounselorRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): Counselor;
    public function update(int $id, array $data): Counselor;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function assignSection(int $counselorId, int $sectionId, int $academicYearId): bool;
    public function unassignSection(int $counselorId, int $sectionId, int $academicYearId): bool;
    public function getSections(int $counselorId, int $academicYearId);
    public function generateCounselorId(): string;
}
