<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
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

        $module = Module::where('name','moving')->first();

        if(!empty($module)) {

            if($module->routes->isEmpty()) {

                $route = Route::factory()->create([
                    'name'  => 'customers',
                    'controllers' => 'CustomerController',
                    'image' => 'mdi-account-arrow-left',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);

                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'rooms',
                    'controllers' => 'RoomController',
                    'image' => 'mdi-widgets',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);

                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'items',
                    'controllers' => 'ItemController',
                    'image' => 'mdi-scatter-plot',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);

                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'orders',
                    'controllers' => 'OrderController',
                    'image' => 'mdi-package-variant',
                    'is_menu' => 1,
                    'module_id' => $module->id,
                ]);

                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'sellers',
                    'controllers' => 'SellerController',
                    'image' => 'mdi-package-variant',
                    'is_menu' => 0,
                    'module_id' => $module->id,
                ]);

                $this->command->info("INFO: Route was created: {$route->name}");

                $route = Route::factory()->create([
                    'name'  => 'packings',
                    'controllers' => 'PackingController',
                    'image' => 'mdi-package-variant-closed',
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
