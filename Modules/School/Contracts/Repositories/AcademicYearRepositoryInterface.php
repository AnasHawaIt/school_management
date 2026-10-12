<?php

namespace Modules\School\Contracts\Repositories;

use Modules\Shared\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AcademicYearRepositoryInterface extends BaseRepositoryInterface
{
    public function getCurrent();

    public function getActive(): Collection;

    public function getWithSemesters(int $id);
}
