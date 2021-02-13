<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedPlanNotFound;
use Modules\System\Entities\Plan;
use Modules\System\Entities\Tenant;

class TenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $Plan = Plan::first();

        if(!empty($Plan)) {

            $Tenant = Tenant::first();

            if(empty($Tenant)) {
                $TenantNova = Tenant::factory()->create([
                        'name'          => 'LippoAraujo',
                        'trading_name'  => 'LippoAraujo',
                        'email'         => config('api.apiEmail'),
                        'plan_id'          => $Plan->id
                    ]);
                $this->command->info("INFO: Tenant was created: {$TenantNova->name}");
            } else {
                $this->command->warn("INFO: Tenant alredy exist: {$Tenant->name}");
            }
        } else {
            $message = 'ERROR: Plan not found!';
            $this->command->error($message);
            throw new SeedPlanNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
