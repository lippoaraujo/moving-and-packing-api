<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\Moving\Entities\Item;
use Modules\System\Entities\Tenant;
use Illuminate\Database\Eloquent\Model;
use Modules\Moving\Database\Seeders\Exceptions\SeedPackingNotFound;
use Modules\Moving\Entities\Packing;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;

class ItemTableseeder extends Seeder
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

            $packing = Packing::first();

            if(!empty($packing)) {
                $items = Item::factory()->count(10)->create([
                    'packing_id'    => $packing->id,
                    'tenant_id'     => $tenant->id
                ]);

                $this->command->info("INFO: amount of items created: {$items->count()}");

                foreach($items as $item) {
                    $this->command->warn("INFO: Item: {$item->name} was created");
                    $this->command->info('##############################');
                }
            } else {
                $message = 'ERROR: Packing not found!';
                $this->command->error($message);
                throw new SeedPackingNotFound($message, Response::HTTP_NOT_FOUND);
            }
        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
