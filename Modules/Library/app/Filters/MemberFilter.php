<?php

namespace Modules\Library\app\Filters;

use App\Filters\QueryFilter;

class MemberFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where(function ($q) use ($value) {
            $q->where('name', 'like', "%$value%")
                ->orWhere('email', 'like', "%$value%");
        });
    }
}
