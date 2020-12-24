<?php

namespace Modules\System\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\System\Repositories\Contracts\{
    ActionRepositoryInterface,
    DashboardRepositoryInterface,
    ModuleRepositoryInterface,
    RouteRepositoryInterface,
    TenantRepositoryInterface,
    UsergroupRepositoryInterface,
    UserRepositoryInterface,
};

use Modules\System\Repositories\Eloquent\{
    EloquentActionRepository,
    EloquentDashboardRepository,
    EloquentModuleRepository,
    EloquentRouteRepository,
    EloquentTenantRepository,
    EloquentUsergroupRepository,
    EloquentUserRepository,
};

use Modules\System\Repositories\QueryBuilder\{
    QueryBuilderUserRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(
        //     UserRepositoryInterface::class,
        //     QueryBuilderUserRepository::class
        // );
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
        $this->app->bind(
            TenantRepositoryInterface::class,
            EloquentTenantRepository::class
        );
        $this->app->bind(
            UsergroupRepositoryInterface::class,
            EloquentUsergroupRepository::class
        );
        $this->app->bind(
            ModuleRepositoryInterface::class,
            EloquentModuleRepository::class
        );
        $this->app->bind(
            RouteRepositoryInterface::class,
            EloquentRouteRepository::class
        );
        $this->app->bind(
            ActionRepositoryInterface::class,
            EloquentActionRepository::class
        );
        $this->app->bind(
            DashboardRepositoryInterface::class,
            EloquentDashboardRepository::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
