<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\User;
use App\Repositories\Core\BaseEloquentRepository;
use Illuminate\Support\Collection;
use Modules\System\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    public function entity()
    {
        return User::class;
    }

    public function getUserAuthPermissions(): Collection
    {
        $user        = auth('api')->user();
        $tenantPlan  = $user->tenant->plan;
        $modulesAble = $tenantPlan->modules;

        $permissions['permissions'] = [
            'can'           => $user->getAllPermissions(),
            'plan_modules'  => $modulesAble
        ];

        return collect($permissions);
    }
}
