<?php

namespace Modules\System\Database\Seeders\Start;

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

class ActionRouteUserTableSeeder extends Seeder
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
        $this->command->info("INFO: starting User Action Route relationships");
        $this->command->info("**********************************************");

        $usergroup = Usergroup::where('name','master')->first();

        if(!empty($usergroup)){
            $this->command->info("Usergroup {$usergroup->name} was found with id: {$usergroup->id}");

            $users = $usergroup->users()->get();

            if(!$users->isEmpty()) {

                $this->command->info("Total Users from group {$usergroup->name} found: {$users->count()}");
                $this->command->info(" ");

                $module = Module::where('name', 'system')->with('routes')->first();

                $routes = $module->routes;

                if(!empty($routes)) {

                    $actions = Action::all();

                    if($actions->isEmpty()) {
                        $message = 'ERROR: Actions not found!';
                        $this->command->error($message);
                        throw new SeedActionsNotFound($message, Response::HTTP_NOT_FOUND);
                    }

                        foreach($users as $user) {
                            $this->command->info("==========================");
                            $this->command->info("INFO: processing routes actions for user: {$user->name}");
                            $this->command->info(" ");

                            foreach($routes as $route) {

                                foreach($actions as $action) {
                                    $user->routes()->attach($route->id, ['action_id' => $action->id]);
                                    $this->command->info("===========================================================");
                                    $this->command->warn("action: {$action->name} was attached with route: {$route->name}");
                                    $this->command->info("===========================================================");
                                }
                            }
                            $this->command->info("processed...");
                            $this->command->info("==========================");
                        }

                        $this->command->info("User action route relationships was succefully processed!");
                        $this->command->info(" ");
                        $this->command->info("**********************************************");

                } else {
                    $message = 'ERROR: Routes not found!';
                    $this->command->error($message);
                    throw new SeedRoutesNotFound($message, Response::HTTP_NOT_FOUND);
                }
            } else {
                $message = "ERROR: users from group: {$usergroup->name} not found!";
                $this->command->error($message);
                throw new SeedUsersNotFound($message, Response::HTTP_NOT_FOUND);
            }
        } else {
            $message = 'ERROR: Usergroup not found!';
            $this->command->error($message);
            throw new SeedUsergroupNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
