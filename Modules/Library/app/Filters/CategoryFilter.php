<?php

namespace Modules\Library\app\Filters;

use App\Filters\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }
}
