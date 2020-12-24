<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Modules\System\Database\Seeders\Exceptions\SeedModuleNotFound;
use \Modules\System\Entities\Module;
use \Modules\System\Entities\Route;

class RouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $module = Module::where('name','system')->first();

        if(!empty($module)) {

            $routes = Route::where('module_id',$module->id)->get();

            if(empty($routes->toArray())){

                // $route = Route::factory()->create([
                //     'name'  => 'modules',
                //     'controllers' => 'ModuleController',
                //     'module_id' => $module->id,
                // ]);
                // $this->command->info("INFO: Route was created: {$route->name}");

                // $route = Route::factory()->create([
                //     'name'  => 'routes',
                //     'controllers' => 'RouteController',
                //     'module_id' => $module->id,
                // ]);
                // $this->command->info("INFO: Route was created: {$route->name}");

                // $route = Route::factory()->create([
                //     'name'  => 'actions',
                //     'controllers' => 'ActionsController',
                //     'module_id' => $module->id,
                // ]);
                // $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'users',
                    'controllers' => 'UserController',
                    'image' => 'mdi-account-tie',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);
                $this->command->info("INFO: Route was created: {$route->name}");

                // $route = Route::factory()->create([
                //     'name'  => 'tenants',
                //     'controllers' => 'TenantController',
                //     'module_id' => $module->id,
                // ]);
                // $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'usergroups',
                    'controllers' => 'UsergroupController',
                    'image' => 'mdi-account-group',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);
                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'dashboard',
                    'controllers' => 'DashboardController',
                    'image' => 'mdi-account-settings',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);
                $this->command->info("INFO: Route was created: {$route->name}");

            } else {
                $this->command->warn('INFO: Routes alredy exist!');
            }
        } else {
            $message = 'ERROR: Module not found!';
            $this->command->error($message);
            throw new SeedModuleNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
