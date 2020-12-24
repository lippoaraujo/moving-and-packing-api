<?php

namespace Modules\System\Entities\Traits;

use Illuminate\Support\Str;
use Modules\System\Entities\Route;

Trait UserACL
{

    public function permissionsUserRoutes()
    {
        $routes = $this->routes->unique();

        $permissions = collect();

        $routes->each(function($route) use($permissions) {
            if($route->active) {
                $permissions->push($route);
            }
        });

        return $permissions;
    }

    public function permissionsUsergroupRoutes()
    {
        $routes = $this->usergroup->routes->unique();

        $permissions = collect();

        $routes->each(function($route) use($permissions) {
            if($route->active) {
                $permissions->push($route);
            }
        });

        return $permissions;
    }

    public function hasPermissionUserRoute(Route $route): bool
    {
        $routePermissions = $this->permissionsUserRoutes();

        foreach($routePermissions as $routePermission) {
            if($routePermission->name === $route->name) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionUsergroupRoute(Route $route): bool
    {
        $routePermissions = $this->permissionsUsergroupRoutes();

        foreach($routePermissions as $routePermission) {
            if($routePermission->name === $route->name) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionUserAction(Route $route, string $actioName): bool
    {
        $routePermissions = $this->permissionsUserRoutes();

        foreach($routePermissions as $routePermission) {

            if($routePermission->name === $route->name) {

                $actions = $this->actionsWhere($route->id)->unique();

                foreach($actions as $action) {
                    if($action->active && $action->name === $actioName) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function hasPermissionUsergroupAction(Route $route, string $actioName): bool
    {
        $routePermissions = $this->permissionsUsergroupRoutes();

        foreach($routePermissions as $routePermission) {

            if($routePermission->name === $route->name) {

                $actions = $this->usergroup->actionsWhere($route->id)->unique();

                foreach($actions as $action) {
                    if($action->active && $action->name === $actioName) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return in_array($this->email, config('acl.admins'));
    }

    public function isTenant(): bool
    {
        return !in_array($this->email, config('acl.admins'));
    }

    public function isDefaultUsergroup(): bool
    {
        return $this->usergroup->name === config('acl.usergroups.default');
    }
}
