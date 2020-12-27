<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\System\Entities\Action;
use Modules\System\Entities\Module;
use Modules\System\Entities\Usergroup;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Exceptions\SeedActionsNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedRoutesNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedUsergroupNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedUsersNotFound;

class ActionRouteUsergroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->command->info(" ");
        $this->command->info("**********************************************");
        $this->command->info("INFO: starting Usergroup Action Route relationships");
        $this->command->info("**********************************************");

        $usergroups = Usergroup::all();

        if(!$usergroups->isEmpty()) {
            $this->command->info("Total Usersgroups found: {$usergroups->count()}");
            $this->command->info(" ");

            $module = Module::where('name', 'moving')->with('routes')->first();

            $routes = $module->routes;

            if(!$routes->isEmpty()) {

                $actions = Action::all();

                if($actions->isEmpty()) {
                    $message = 'ERROR: Actions not found!';
                    $this->command->error($message);
                    throw new SeedActionsNotFound($message, Response::HTTP_NOT_FOUND);
                }

                $sellersRoutes          = ['orders', 'items', 'rooms', 'customers', 'sellers'];

                $sellersRoutesIsNotMenu = ['items', 'rooms', 'customers', 'sellers'];
                $sellersActions         = ['index'];

                foreach($usergroups as $usergroup) {
                    $this->command->info("==========================");
                    $this->command->info("INFO: processing routes actions for usergroup: {$usergroup->name}");
                    $this->command->info(" ");

                    foreach($routes as $route) {

                        if($usergroup->name === 'seller' && !in_array($route->name, $sellersRoutes)) {
                            continue;
                        }

                        foreach($actions as $action) {

                            if($usergroup->name === 'seller' &&
                                in_array($route->name, $sellersRoutesIsNotMenu) &&
                                in_array($action->name, $sellersActions)) {
                                $usergroup->routes()->attach($route->id, ['action_id' => $action->id, 'is_menu' => 0]);
                            } else if($usergroup->name === 'seller' && $route->name === 'orders') {
                                $usergroup->routes()->attach($route->id, ['action_id' => $action->id, 'is_menu' => 1]);
                            } else if($usergroup->name !== 'seller') {
                                $usergroup->routes()->attach($route->id, ['action_id' => $action->id, 'is_menu' => 1]);
                            }

                            $this->command->info("===========================================================");
                            $this->command->warn("action: {$action->name} was attached with route: {$route->name}");
                            $this->command->info("===========================================================");
                        }
                    }
                    $this->command->info("processed...");
                    $this->command->info("==========================");
                }

                $this->command->info("Usergroup action route relationships was succefully processed!");
                $this->command->info(" ");
                $this->command->info("**********************************************");
            } else {
                $message = 'ERROR: Routes not found!';
                $this->command->error($message);
                throw new SeedRoutesNotFound($message, Response::HTTP_NOT_FOUND);
            }
        } else {
            $message = 'ERROR: Usergroup not found!';
            $this->command->error($message);
            throw new SeedUsergroupNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
