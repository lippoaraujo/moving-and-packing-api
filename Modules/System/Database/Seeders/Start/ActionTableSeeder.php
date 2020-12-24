<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Modules\System\Entities\Action;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $actions = Action::all();

        if(empty($actions->toArray())){

            $action = Action::factory()->create([
                'name'  => 'index',
                'type' => 'GET',
            ]);
            $this->command->info("INFO: Action was created: {$action->name}");

            $action = Action::factory()->create([
                'name'  => 'show',
                'type' => 'GET',
            ]);
            $this->command->info("INFO: Action was created: {$action->name}");

            $action = Action::factory()->create([
                'name'  => 'store',
                'type' => 'POST',
            ]);
            $this->command->info("INFO: Action was created: {$action->name}");

            $action = Action::factory()->create([
                'name'  => 'update',
                'type' => 'PUT',
            ]);
           $this->command->info("INFO: Action was created: {$action->name}");

            $action = Action::factory()->create([
                'name'  => 'destroy',
                'type' => 'DELETE',
            ]);
            $this->command->info("INFO: Action was created: {$action->name}");

        } else {
            $this->command->warn("INFO: Actions alredy exist!");
        }
    }
}
