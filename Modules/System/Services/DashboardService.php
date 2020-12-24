<?php

namespace Modules\System\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\System\Entities\User;
use Modules\System\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService extends Controller
{
    private $repo;

    public function __construct(DashboardRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Show the specified resource.
     * @return array
     */
    public function show($id)
    {
        $user = $this->repo->relationships('routes', 'usergroup')->findById($id);

        $userRoutes      = $user->routes->unique();
        $usergroupRoutes = $user->usergroup->routes->unique();

        $routes = $userRoutes->merge($usergroupRoutes);

        $modules     = $this->getModules($routes);
        $permissions = $this->getPermissions($user, $modules, $routes);

        $dashboard['modules'] = $permissions->toArray();
        return $dashboard;
    }

    /**
     * get all active route modules
     *
     * @param \Illuminate\Support\Collection $routes
     *
     * @return Collection
     */
    private function getModules(Collection $routes)
    {
        $modules = collect();

        foreach ($routes as $route) {
            $module = $route->module;
            if (!$modules->contains($module)) {
                $modules->push($route->module);
            }
        }

        return $modules;
    }

    /**
     * get modules structure with all routes permissions
     *
     * @param \Illuminate\Support\Collection $modules
     * @param \Illuminate\Support\Collection $routes
     *
     * @return Collection
     */
    private function getPermissions(User $user, Collection $modules, Collection $routes)
    {
        $permissions = collect();
        foreach ($modules as $module) {
            if (!$module->active) {
                continue;
            }

            $module->routes_permissions = collect();

            foreach ($routes as $route) {
                if ($route->module->id === $module->id) {
                    $route->unsetRelation('module');

                    $actions = $user->actionsWhere($route->id)->merge($user->usergroup->actionsWhere($route->id));

                    $actions = $actions->filter(function ($action) {
                        return $action->active;
                    });

                    $route->makeHidden('pivot', 'actionsUsergroup', 'actionsUser');

                    $route->actions = $actions->toArray();

                    if ($route->active) {
                        $module->routes_permissions->push($route->toArray());
                    }
                }
            }

            $module->routes_permissions = $module->routes_permissions->toArray();

            $permissions->push($module->toArray());
        }

        return $permissions;
    }
}
