<?php

namespace Modules\System\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['validator']->extend('entity_disabled', function ($attribute, $value, $parameters)
        {
            if(!empty($parameters[0])) {
                $table = $parameters[0];

                if(!empty($parameters[1])) {
                    $column = $parameters[1];
                } else {
                    $column = $attribute;
                }

                $result = DB::table($table)->where($column, $value)->whereNotNull('deleted_at')->get();

                if(!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }, 'Disabled entity found with this :attribute.');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
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
