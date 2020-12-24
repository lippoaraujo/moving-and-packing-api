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
        //  \Illuminate\Database\Eloquent\Model::clearBootedModels();

        if (auth('api')->check()) {
            $user = auth('api')->user();

            if ($user->tenant_id && $user->isTenant()) {
                Landlord::addTenant('tenant_id', $user->tenant_id);
                Landlord::applyTenantScopesToDeferredModels();
            }
        }

        return $next($request);
    }
}
