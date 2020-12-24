<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\Moving\Entities\Item;
use Modules\System\Entities\Tenant;
use Illuminate\Database\Eloquent\Model;
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
            $items = Item::factory()->count(10)->create([
                'tenant_id' => $tenant->id
            ]);

            $this->command->info("INFO: amount of items created: {$items->count()}");

            foreach($items as $item) {
                $this->command->warn("INFO: Item: {$item->name} was created");
                $this->command->info('##############################');
            }
        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
