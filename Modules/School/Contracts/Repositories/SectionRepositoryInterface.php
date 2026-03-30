<?php

namespace Modules\School\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface SectionRepositoryInterface extends BaseRepositoryInterface
{
    public function getActive(): Collection;

    public function getByClass(int $classId): Collection;

    public function getAvailable(): Collection;
}
