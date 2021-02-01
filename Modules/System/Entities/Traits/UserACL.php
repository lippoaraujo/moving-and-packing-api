<?php

namespace Modules\System\Entities\Traits;

use Modules\System\Entities\Role;

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

    public function isMasterTenantRole(): bool
    {
        return $this->hasRole(config('acl.tenant.default_role'));
    }

    public function isMasterTenant(): bool
    {
        return $this->email === $this->tenant->email;
    }
}
