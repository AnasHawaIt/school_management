<?php

namespace Modules\Transport\Filters;

use Modules\Shared\Filters\QueryFilter;

class BusFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }

    public function capacity($value)
    {
        $this->query->where('capacity', $value);
    }
}
