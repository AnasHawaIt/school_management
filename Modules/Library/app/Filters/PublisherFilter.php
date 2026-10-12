<?php

namespace Modules\Library\Filters;

use Modules\Shared\Filters\QueryFilter;

class PublisherFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }
}
