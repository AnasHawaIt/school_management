<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\AcademicYear;
use Illuminate\Database\Eloquent\Collection;

interface AcademicYearServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?AcademicYear;

    public function create(array $data): AcademicYear;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function setCurrent(int $id): bool;

    public function getCurrent();
}
