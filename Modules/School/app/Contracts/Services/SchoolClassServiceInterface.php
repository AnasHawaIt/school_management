<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\SchoolClass;
use Illuminate\Database\Eloquent\Collection;

interface SchoolClassServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?SchoolClass;

    public function create(array $data): SchoolClass;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByGrade(int $gradeId): Collection;
}
