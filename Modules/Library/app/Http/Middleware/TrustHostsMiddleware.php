<?php

namespace Modules\Library\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustHostsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function hosts()
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
