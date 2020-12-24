<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Database\Seeders\Start\{
    ActionRouteTableSeeder,
    ActionRouteUsergroupTableSeeder,
    ActionRouteUserTableSeeder,
    ActionTableSeeder,
    ModuleTableSeeder,
    RouteTableSeeder,
    TenantTableSeeder,
    UsergroupTableSeeder,
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

        //config users
        $this->call(TenantTableSeeder::class);
        $this->call(UsergroupTableSeeder::class);
        $this->call(UserTableSeeder::class);

        //config modules
        $this->call(ModuleTableSeeder::class);
        $this->call(RouteTableSeeder::class);
        $this->call(ActionTableSeeder::class);

        //config relationships
        // $this->call(ActionRouteTableSeeder::class);
        $this->call(ActionRouteUserTableSeeder::class);
        $this->call(ActionRouteUsergroupTableSeeder::class);
    }
}
