<?php

namespace Modules\Finance\Repositories;

use App\Repositories\BaseRepository;
use Modules\Finance\Contracts\Repositories\DiscountRepositoryInterface;
use Modules\Finance\Entities\Discount;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }
}
