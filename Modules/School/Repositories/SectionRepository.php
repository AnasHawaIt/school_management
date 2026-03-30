<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\SectionRepositoryInterface;
use Modules\School\Entities\Section;
use Illuminate\Database\Eloquent\Collection;

class SectionRepository extends BaseRepository implements SectionRepositoryInterface
{
    public function __construct(Section $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->with('class')->get();
    }

    public function getByClass(int $classId): Collection
    {
        return $this->model->byClass($classId)->active()->get();
    }

    public function getAvailable(): Collection
    {
        return $this->model->available()->active()->get();
    }
}
