<?php

namespace Lingui\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $i18n = app('i18n');

        Inertia::share([
            'i18n' => [
                'translation' => $i18n->translation(),
            ],
        ]);

        return $next($request);
    }
}
