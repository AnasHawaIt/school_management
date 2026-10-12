<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\Grade;
use Illuminate\Database\Eloquent\Collection;

interface GradeServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Grade;

    public function create(array $data): Grade;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByLevel(string $level): Collection;
}
