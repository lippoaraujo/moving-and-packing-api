<?php

namespace Modules\System\Database\Seeders\Traits;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait AdminSync
{
    public function syncAllPermission() : Role
    {
        $role = Role::findByName('Admin');

        $permissions = Permission::pluck('id','id')->all();

        return $role->syncPermissions($permissions);
    }
}
