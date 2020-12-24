<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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

        $Tenant = Tenant::first();

        if(empty($Tenant)) {
            $TenantNova = Tenant::factory()->create([
                    'name'          => 'LippoAraujo',
                    'trading_name'  => 'LippoAraujo',
                    'email'         => config('api.apiEmail'),
                ]);
            $this->command->info("INFO: Tenant was created: {$TenantNova->name}");
        } else {
            $this->command->warn("INFO: Tenant alredy exist: {$Tenant->name}");
        }
    }
}
