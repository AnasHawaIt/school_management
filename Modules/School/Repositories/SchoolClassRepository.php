<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\SchoolClassRepositoryInterface;
use Modules\School\Entities\SchoolClass;
use Illuminate\Database\Eloquent\Collection;

class SchoolClassRepository extends BaseRepository implements SchoolClassRepositoryInterface
{
    public function __construct(SchoolClass $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->with(['grade', 'academicYear'])->get();
    }

    public function getByGrade(int $gradeId): Collection
    {
        return $this->model->byGrade($gradeId)->active()->get();
    }

    public function getByAcademicYear(int $academicYearId): Collection
    {
        return $this->model->byAcademicYear($academicYearId)->active()->get();
    }

    public function getWithSections(int $id)
    {
        return $this->model->with(['sections', 'grade', 'academicYear'])->find($id);
    }
}
