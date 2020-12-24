<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\Moving\Entities\Item;
use Modules\Moving\Entities\Room;
use Modules\System\Entities\Tenant;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;

class RoomTableSeeder extends Seeder
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
            $rooms = Room::factory()->count(6)->create([
                'tenant_id' => $tenant->id
            ]);

            $this->command->info("INFO: amount of rooms created: {$rooms->count()}");

            foreach($rooms as $room) {
                $this->command->warn("INFO: Room: {$room->name} was created");
                $this->command->info('##############################');
            }
        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
