<?php

namespace Lingui\Http\Middleware;

use Closure;
use Lingui\Lingui;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share(Lingui::inertiaProps());

        return $next($request);
    }
}
