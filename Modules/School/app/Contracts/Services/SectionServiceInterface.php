<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\Section;
use Illuminate\Database\Eloquent\Collection;

interface SectionServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Section;

    public function create(array $data): Section;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByClass(int $classId): Collection;
}
