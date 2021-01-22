<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use Modules\System\Database\Seeders\Traits\AdminSync;
use \Modules\System\Entities\Tenant;
use Spatie\Permission\Models\Permission;
use Modules\System\Entities\Role;
class RoleTableSeeder extends Seeder
{
    use AdminSync;
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

            $role = Role::where(['name' => 'Admin'])->first();

            // create role Admin test with all permissions
            if(empty($role)) {

                $role = Role::create([
                    'name' => 'Admin',
                    'tenant_id' => $tenant->id
                ]);

                $permissions = Permission::pluck('id','id')->all();

                $role->syncPermissions($permissions);

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
