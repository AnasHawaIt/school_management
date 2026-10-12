<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\Semester;
use Illuminate\Database\Eloquent\Collection;

interface SemesterServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Semester;

    public function create(array $data): Semester;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByAcademicYear(int $academicYearId): Collection;

    public function setCurrent(int $id): bool;

    public function getCurrent();
}
