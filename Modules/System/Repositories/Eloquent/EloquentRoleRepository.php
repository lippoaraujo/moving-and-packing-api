<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Role;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\RoleRepositoryInterface;

class EloquentRoleRepository extends BaseEloquentRepository implements RoleRepositoryInterface
{
    public function entity()
    {
        return Role::class;
    }
}
