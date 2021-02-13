<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Start\{
    ModuleTableSeeder,
    PermissionTableSeeder,
    PlanHasModulesTableSeeder,
    PlanTableSeeder,
    RoleTableSeeder,
    TenantTableSeeder,
    UserSuperAdminTableSeeder,
    UserTableSeeder
};

class SystemDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ModuleTableSeeder::class);
        $this->call(PlanTableSeeder::class);
        $this->call(PlanHasModulesTableSeeder::class);
        $this->call(TenantTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserSuperAdminTableSeeder::class);

        $env_app = app()->environment();
        if ($env_app === 'local' || $env_app === 'testing') {
            $this->call(RoleTableSeeder::class);
            $this->call(UserTableSeeder::class);
        }
    }
}
