<?php

namespace Modules\Library\app\Filters;

use App\Filters\QueryFilter;

class AuthorFilter extends QueryFilter
{
    public function search($value)
    {
        $this->query->where('name', 'like', "%$value%");
    }

    public function country($value)
    {
        $this->query->where('country', $value);
    }
}
