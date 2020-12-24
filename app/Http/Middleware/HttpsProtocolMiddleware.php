<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class HttpsProtocolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if (!$request->secure() && (app()->environment('production') || app()->environment('testing'))) {
            // return redirect()->secure($request->getRequestUri());
        // }

        return $next($request);
    }
}
