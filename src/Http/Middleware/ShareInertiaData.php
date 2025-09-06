<?php

namespace Lingui\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lingui\Lingui;

class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share(Lingui::inertiaProps());

        return $next($request);
    }
}
