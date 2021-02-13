<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use Modules\System\Entities\Permission;
use Modules\System\Entities\Tenant;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::first();

        if(!empty($tenant)) {
            foreach (config('acl.permissions.can') as $permission) {
                Permission::create([
                    'name' => $permission,
                    'tenant_id' => $tenant->id
                ]);
            }

            $env_app = app()->environment();
            if ($env_app === 'local' || $env_app === 'testing') {
                foreach (config('acl.permissions.modules') as $permission) {
                    Permission::create([
                        'name' => $permission,
                        'tenant_id' => $tenant->id
                    ]);
                }
            }
        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}
