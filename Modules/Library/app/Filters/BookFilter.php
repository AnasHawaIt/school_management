<?php

namespace Modules\Library\app\Filters;

use App\Filters\QueryFilter;
use Modules\Library\app\Enums\BookCopiesStatus;

class BookFilter extends QueryFilter
{
    public function author_id($value)
    {
        $this->query->where('author_id', $value);
    }

    public function category_id($value)
    {
        $this->query->where('category_id', $value);
    }

    public function available($value)
    {
        if ($value) {
            $this->query->whereHas('copies', function ($query) {
                $query->where('status', BookCopiesStatus::AVAILABLE);
            });
        }
    }

    public function search($value)
    {
        $this->query->where(function ($q) use ($value) {
            $q->where('title', 'like', "%$value%");
        });
    }
}
