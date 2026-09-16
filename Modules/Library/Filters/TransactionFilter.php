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
        $this->query->whereDate('borrow_date', '>=', $value);
    }

    public function to_date($value)
    {
        $this->query->whereDate('borrow_date', '<=', $value);
    }

    public function sort($value)
    {
        $column = in_array($value, ['borrow_date', 'due_date', 'created_at', 'status'], true)
            ? $value
            : 'created_at';
        $direction = $this->request->get('direction') === 'asc' ? 'asc' : 'desc';

        $this->query->orderBy($column, $direction);
    }
}
