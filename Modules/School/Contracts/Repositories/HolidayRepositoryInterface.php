<?php

namespace Modules\School\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface HolidayRepositoryInterface extends BaseRepositoryInterface
{
    public function getByAcademicYear(int $academicYearId): Collection;

    public function getUpcoming(): Collection;

    public function getByType(string $type): Collection;
}
