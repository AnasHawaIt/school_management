<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\AcademicYearRepositoryInterface;
use Modules\School\Entities\AcademicYear;
use Illuminate\Database\Eloquent\Collection;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }

    public function getCurrent()
    {
        return $this->model->current()->first();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getWithSemesters(int $id)
    {
        return $this->model->with('semesters')->find($id);
    }
}
