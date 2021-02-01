<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Modules\System\Entities\Module;
use Modules\System\Entities\Plan;

class PlanHasModulesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $modulesId = Module::pluck('id')->all();

        $plan = Plan::where('name' , 'Basic')->first();

        $plan->modules()->sync($modulesId);
    }
}

