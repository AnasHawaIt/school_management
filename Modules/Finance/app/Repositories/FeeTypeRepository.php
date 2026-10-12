<?php

namespace Modules\Finance\Repositories;

use Modules\Shared\Repositories\BaseRepository;
use Modules\Finance\Contracts\Repositories\FeeTypeRepositoryInterface;
use Modules\Finance\Entities\FeeType;

class FeeTypeRepository extends BaseRepository implements FeeTypeRepositoryInterface
{
    public function __construct(FeeType $model)
    {
        parent::__construct($model);
    }
}
