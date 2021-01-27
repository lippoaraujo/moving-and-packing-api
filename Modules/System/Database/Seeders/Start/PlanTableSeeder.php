<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Modules\System\Entities\Plan;
use Modules\System\Entities\Module;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            0 => [
                'name' => 'Basic',
                'price' => '50',
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
