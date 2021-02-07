<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use \Modules\System\Entities\Tenant;
use Modules\System\Entities\Role;

class RoleTableSeeder extends Seeder
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

            $role = Role::where(['name' => 'Seller'])->first();

            // create role Admin test with all permissions
            if(empty($role)) {
                $role = Role::create([
                    'name' => 'Seller',
                    'tenant_id' => $tenant->id
                ]);

                $permissions = [
                    'item-list',
                    'room-list',
                    'seller-list',

                    'packing-list',

                    'order-list',
                    'order-create',
                    'order-show',
                    'order-edit',
                    'order-delete',

                    'customer-list',
                    'customer-create',
                    'customer-show',
                    'customer-edit',
                    'customer-delete',
                ];

                $role->givePermissionTo($permissions);

                $this->command->info("INFO: Role was created: {$role->name}");
            } else{
                $this->command->warn("INFO: Role alredy exist: {$role->name}");
            }

        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
