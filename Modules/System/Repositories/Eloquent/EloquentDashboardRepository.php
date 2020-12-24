<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\User;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\DashboardRepositoryInterface;

class EloquentDashboardRepository extends BaseEloquentRepository implements DashboardRepositoryInterface
{
    public function entity()
    {
        return User::class;
    }
}
