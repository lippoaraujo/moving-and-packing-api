<?php

namespace Modules\System\Repositories\Eloquent;

use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Entities\Permission;
use Modules\System\Repositories\Contracts\PermissionRepositoryInterface;

class EloquentPermissionRepository extends BaseEloquentRepository implements PermissionRepositoryInterface
{
    public function entity()
    {
        return Permission::class;
    }
}
