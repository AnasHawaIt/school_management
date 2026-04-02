<?php
namespace App\Filters;

abstract class QueryFilter extends BaseFilter
{
    protected $query;

    public function apply($query)
    {
        $this->query = $query;

        foreach ($this->request->all() as $name => $value) {
            if ($this->isValid($name, $value)) {
                $this->$name($value);
            }
        }

        return $this->query;
    }

    protected function isValid($name, $value)
    {
        return method_exists($this, $name) && filled($value);
    }
}
