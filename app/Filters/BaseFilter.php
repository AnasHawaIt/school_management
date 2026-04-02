<?php
namespace App\Filters;

use Illuminate\Http\Request;

abstract class BaseFilter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getValue($key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    protected function has($key)
    {
        return $this->request->filled($key);
    }
}
