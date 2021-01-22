<?php

namespace Modules\Moving\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Modules\Moving\Database\Seeders\Start\{
    CustomerTableSeeder,
    ItemTableseeder,
    ModuleTableSeeder,
    OrderTableSeeder,
    PackingTableSeeder,
    PermissionTableSeeder,
    RoleTableSeeder,
    RoomTableSeeder,
    UserTableSeeder,
};

class MovingDatabaseSeeder extends Seeder
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
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);

        $env_app = app()->environment();
        if ($env_app === 'local' || $env_app === 'testing') {
            $this->call(UserTableSeeder::class);
            $this->call(CustomerTableSeeder::class);
            $this->call(RoomTableSeeder::class);
            $this->call(PackingTableSeeder::class);
            $this->call(ItemTableseeder::class);
            $this->call(OrderTableSeeder::class);
        }
    }
}
