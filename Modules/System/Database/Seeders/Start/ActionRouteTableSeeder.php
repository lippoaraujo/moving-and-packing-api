<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\System\Entities\Action;
use Modules\System\Entities\Module;
use Modules\System\Entities\UserGroup;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Exceptions\SeedActionsNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedRoutesNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedUsergroupNotFound;
use Modules\System\Entities\Route;

class ActionRouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $routes = Route::all();

        if($routes->isEmpty()) {
            $message = 'ERROR: Routes not found!';
            $this->command->error($message);
            throw new SeedRoutesNotFound($message, Response::HTTP_NOT_FOUND);
        }

        $actions = Action::all();

        if($actions->isEmpty()) {
            $message = 'ERROR: Actions not found!';
            $this->command->error($message);
            throw new SeedActionsNotFound($message, Response::HTTP_NOT_FOUND);
        }

        $this->command->info("==========================");
        $this->command->info("INFO: processing routes actions relationships");

        foreach($routes as $route) {
            $route->actions()->attach($actions);
        }
        $this->command->info("==========================");
        $this->command->info("processed...");
        $this->command->info("==========================");
    }
}
