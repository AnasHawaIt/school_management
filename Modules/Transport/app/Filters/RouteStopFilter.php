<?php

namespace Modules\Transport\Filters;

use App\Filters\QueryFilter;

class RouteStopFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where(function ($q) use ($value) {
            $q->where('name', 'like', "%$value%")
                ->orWhere('sequence', 'like', "%$value%");
        });
    }
}
