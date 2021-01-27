<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as BaseRole;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Role extends BaseRole
{
    use BelongsToTenants, SoftDeletes, HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\System\Database\factories\RoleFactory::new();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
