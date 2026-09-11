<?php

namespace Modules\Library\Filters;

use App\Filters\QueryFilter;

class PublisherFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }
}
