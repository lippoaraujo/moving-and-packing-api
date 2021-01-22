<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Modules\System\Database\Seeders\Traits\AdminSync;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    use AdminSync;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            'item-list',
            'item-create',
            'item-edit',
            'item-delete',

            'room-list',
            'room-create',
            'room-edit',
            'room-delete',

            'packing-list',
            'packing-create',
            'packing-edit',
            'packing-delete',

            'order-list',
            'order-create',
            'order-edit',
            'order-delete',

            'seller-list'
        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }

        $role = $this->syncAllPermission();
    }
}