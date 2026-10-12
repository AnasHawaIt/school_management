<?php

namespace Modules\Transport\Filters;

use Modules\Shared\Filters\QueryFilter;

class RouteFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }
}
