<?php

namespace Modules\School\Repositories;

use App\Repositories\BaseRepository;
use Modules\School\Contracts\Repositories\GradeRepositoryInterface;
use Modules\School\Entities\Grade;
use Illuminate\Database\Eloquent\Collection;

class GradeRepository extends BaseRepository implements GradeRepositoryInterface
{
    public function __construct(Grade $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function getByLevel(string $level): Collection
    {
        return $this->model->byLevel($level)->ordered()->get();
    }

    public function getOrdered(): Collection
    {
        return $this->model->ordered()->get();
    }
}
