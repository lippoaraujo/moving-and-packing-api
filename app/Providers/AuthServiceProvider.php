<?php

namespace App\Providers;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Http\Response;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Modules\System\Entities\Action;
use Modules\System\Entities\Route;
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

        // Gate::define('owner', function(User $user, $entity) {
        //     return $user->id === $entity->user_id;
        // });

        $routes = collect();
        if(Schema::hasTable('routes')) {
            $routes = Route::all();
        }

        foreach($routes as $route) {

            $module = $route->module;

            Gate::define($route->name, function(User $user, $actionName = null) use($route, $module) {

                if(!$module->active) {
                    return Response::deny("module: {$module->name} was disabled unauthorized!");
                }

                if($user->isTenant() && $user->isDefaultUsergroup()) {
                    return Response::allow();
                }

                if(!empty($actionName)) {
                    if($user->hasPermissionUserAction($route, $actionName) || $user->hasPermissionUsergroupAction($route, $actionName)) {
                        return Response::allow();
                    } else {
                        return Response::deny('action access unauthorized!');
                    }
                }

                if($user->hasPermissionUserRoute($route) || $user->hasPermissionUsergroupRoute($route)) {
                    return Response::allow();
                } else {
                    return Response::deny('route access unauthorized!');
                }

                return Response::allow();
            });
        }

        Gate::before(function(User $user) {
            if($user->isAdmin()) {
                return Response::allow();
            }
        });

    }
}
