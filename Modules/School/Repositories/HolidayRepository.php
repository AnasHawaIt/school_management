<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\HolidayRepositoryInterface;
use Modules\School\Entities\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayRepository extends BaseRepository implements HolidayRepositoryInterface
{
    public function __construct(Holiday $model)
    {
        parent::__construct($model);
    }

    public function getByAcademicYear(int $academicYearId): Collection
    {
        return $this->model->byAcademicYear($academicYearId)->orderBy('start_date')->get();
    }

    public function getUpcoming(): Collection
    {
        return $this->model->upcoming()->orderBy('start_date')->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->byType($type)->orderBy('start_date')->get();
    }
}
