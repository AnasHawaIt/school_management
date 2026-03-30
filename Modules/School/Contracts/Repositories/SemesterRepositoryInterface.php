<?php

namespace Modules\School\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface SemesterRepositoryInterface extends BaseRepositoryInterface
{
    public function getCurrent();

    public function getByAcademicYear(int $academicYearId): Collection;
}
