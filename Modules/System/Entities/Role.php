<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as BaseRole;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Role extends BaseRole
{
    use BelongsToTenants, SoftDeletes;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
