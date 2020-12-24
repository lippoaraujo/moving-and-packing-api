<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Moving\Entities\Address;
use Modules\Moving\Entities\Customer;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use Modules\System\Entities\Tenant;

class CustomerTableSeeder extends Seeder
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
            $customer = Customer::factory()->create([
                'tenant_id' => $tenant->id,
            ]);

            // $addressData = Address::factory()->make();

            // $address = $customer->adresses()->create($addressData->toArray());
            $address = Address::factory()->create([
                'tenant_id' => $tenant->id,
            ]);
            $customer->setprimaryAddress($address);
            $customer->save();

            $this->command->info("INFO: customer was created: {$customer->name}");
            $this->command->info("INFO: customer default address was created: {$address->address}");

        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }

}
