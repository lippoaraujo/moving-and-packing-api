<?php

namespace Modules\System\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\System\Repositories\Contracts\{
    ModuleRepositoryInterface,
    RoleRepositoryInterface,
    TenantRepositoryInterface,
    UserRepositoryInterface,
};

use Modules\System\Repositories\Eloquent\{
    EloquentModuleRepository,
    EloquentRoleRepository,
    EloquentTenantRepository,
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
            ModuleRepositoryInterface::class,
            EloquentModuleRepository::class
        );
        $this->app->bind(
            RoleRepositoryInterface::class,
            EloquentRoleRepository::class
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
