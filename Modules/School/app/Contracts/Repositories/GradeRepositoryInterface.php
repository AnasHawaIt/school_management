<?php

namespace Modules\School\Contracts\Repositories;

use Modules\Shared\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface GradeRepositoryInterface extends BaseRepositoryInterface
{
    public function getActive(): Collection;

    public function getByLevel(string $level): Collection;

    public function getOrdered(): Collection;
}
