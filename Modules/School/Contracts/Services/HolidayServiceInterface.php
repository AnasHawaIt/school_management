<?php

namespace Modules\School\Contracts\Services;

use Modules\School\Entities\Holiday;
use Illuminate\Database\Eloquent\Collection;

interface HolidayServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Holiday;

    public function create(array $data): Holiday;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getUpcoming(): Collection;
}
