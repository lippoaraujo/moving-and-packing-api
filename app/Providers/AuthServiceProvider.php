<?php

namespace App\Providers;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Modules\System\Entities\User;

class AuthServiceProvider extends ServiceProvider
{
    use ApiResponser;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function(User $user) {

            if($user->isSuperAdmin() || ($user->isTenant() && $user->isMasterTenant())) {
                return Response::allow();
            }

            return null;
        });
    }
}
