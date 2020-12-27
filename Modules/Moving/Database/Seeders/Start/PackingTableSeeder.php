<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\Moving\Entities\Packing;
use Modules\System\Entities\Tenant;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;

class PackingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $tenant = Tenant::first();

        if(!empty($tenant)) {
            $packings = Packing::factory()->count(5)->create([
                'tenant_id' => $tenant->id
            ]);

            $this->command->info("INFO: amount of rooms created: {$packings->count()}");

            foreach($packings as $packing) {
                $this->command->warn("INFO: Packing: {$packing->name} was created");
                $this->command->info('##############################');
            }
        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
