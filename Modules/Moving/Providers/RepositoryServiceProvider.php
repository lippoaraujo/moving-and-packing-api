<?php

namespace Modules\Moving\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Moving\Repositories\Contracts\{
    CustomerRepositoryInterface,
    ImageRepositoryInterface,
    ItemRepositoryInterface,
    OrderRepositoryInterface,
    PackingRepositoryInterface,
    RoomRepositoryInterface,
    SellerRepositoryInterface,
};

use Modules\Moving\Repositories\Eloquent\{
    EloquentCustomerRepository,
    EloquentImageRepository,
    EloquentItemRepository,
    EloquentOrderRepository,
    EloquentPackingRepository,
    EloquentRoomRepository,
    EloquentSellerRepository,
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
        $this->app->bind(
            CustomerRepositoryInterface::class,
            EloquentCustomerRepository::class
        );

        $this->app->bind(
            RoomRepositoryInterface::class,
            EloquentRoomRepository::class
        );

        $this->app->bind(
            ItemRepositoryInterface::class,
            EloquentItemRepository::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            EloquentOrderRepository::class
        );

        $this->app->bind(
            ImageRepositoryInterface::class,
            EloquentImageRepository::class
        );

        $this->app->bind(
            SellerRepositoryInterface::class,
            EloquentSellerRepository::class
        );

        $this->app->bind(
            PackingRepositoryInterface::class,
            EloquentPackingRepository::class
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
