<?php

namespace Modules\Finance\Repositories;

use App\Repositories\BaseRepository;
use Modules\Finance\Contracts\Repositories\FeeStructureRepositoryInterface;
use Modules\Finance\Entities\FeeStructure;

class FeeStructureRepository extends BaseRepository implements FeeStructureRepositoryInterface
{
    public function __construct(FeeStructure $model)
    {
        parent::__construct($model);
    }
}
