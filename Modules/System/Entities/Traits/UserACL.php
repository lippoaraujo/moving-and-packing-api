<?php

namespace Modules\System\Entities\Traits;

use Illuminate\Support\Str;
use Modules\System\Entities\Route;

Trait UserACL
{
    public function isSuperAdmin(): bool
    {
        return in_array($this->email, config('acl.admins'));
    }

    public function isTenant(): bool
    {
        return !in_array($this->email, config('acl.admins'));
    }

    public function isDefaultTenantRole(string $roleName): bool
    {
        return strtolower($roleName)  === strtolower(config('acl.usergroups.default'));
    }
}
