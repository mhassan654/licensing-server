<?php

namespace Mhassan654\LicenseServer\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class LicenseGuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->tokenCan('license-access')) {
            return $next($request);
        }

        return abort(403);
    }
}
