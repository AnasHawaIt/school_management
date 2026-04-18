<?php

namespace Modules\Library\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrimStringsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
