<?php
namespace Modules\Transport\app\Filters;

use App\Filters\QueryFilter;

class SubscriptionFilter extends QueryFilter
{
    public function student_id($value)
    {
        $this->query->where('student_id', $value);
    }

    public function route_id($value)
    {
        $this->query->where('route_id', $value);
    }

    public function status($value)
    {
        $this->query->where('status', $value);
    }

    public function start_date($value)
    {
        $this->query->whereDate('start_date', '>=', $value);
    }

    public function end_date($value)
    {
        $this->query->whereDate('end_date', '<=', $value);
    }
}
