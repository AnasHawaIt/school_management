<?php
namespace Modules\Library\Filters;

use App\Filters\QueryFilter;

class TransactionFilter extends QueryFilter
{
    public function member_id($value)
    {
        $this->query->where('member_id', $value);
    }

    public function book_id($value)
    {
        $this->query->where('book_id', $value);
    }

    public function status($value)
    {
        $this->query->where('status', $value);
    }

    public function from_date($value)
    {
        $this->query->whereDate('created_at', '>=', $value);
    }

    public function to_date($value)
    {
        $this->query->whereDate('created_at', '<=', $value);
    }
}
