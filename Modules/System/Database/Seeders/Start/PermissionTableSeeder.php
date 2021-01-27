<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Modules\System\Entities\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-show',
            'role-edit',
            'role-delete',

            'user-list',
            'user-create',
            'user-show',
            'user-edit',
            'user-delete',
        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
