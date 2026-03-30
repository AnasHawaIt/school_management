<?php

namespace Modules\School\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface SchoolClassRepositoryInterface extends BaseRepositoryInterface
{
    public function getActive(): Collection;

    public function getByGrade(int $gradeId): Collection;

    public function getByAcademicYear(int $academicYearId): Collection;

    public function getWithSections(int $id);
}
