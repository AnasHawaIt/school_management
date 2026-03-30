<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\SemesterRepositoryInterface;
use Modules\School\Entities\Semester;
use Illuminate\Database\Eloquent\Collection;

class SemesterRepository extends BaseRepository implements SemesterRepositoryInterface
{
    public function __construct(Semester $model)
    {
        parent::__construct($model);
    }

    public function getCurrent()
    {
        return $this->model->current()->first();
    }

    public function getByAcademicYear(int $academicYearId): Collection
    {
        return $this->model->byAcademicYear($academicYearId)->orderBy('order')->get();
    }
}
