<?php

namespace App\Http\Middleware;

use Modules\System\Entities\Tenant;
use Closure;
use Modules\System\Entities\User;
use Torzer\Awesome\Landlord\Facades\Landlord;

class TenantScope
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
        if (auth('api')->check()) {
            $user = auth('api')->user();
            Landlord::addTenant($user->tenant);
            Landlord::applyTenantScopesToDeferredModels();
        }

        return $next($request);
    }
}
