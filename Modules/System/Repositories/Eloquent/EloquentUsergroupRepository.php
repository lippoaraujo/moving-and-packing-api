<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Usergroup;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\UsergroupRepositoryInterface;

class EloquentUsergroupRepository extends BaseEloquentRepository implements UsergroupRepositoryInterface
{
    public function entity()
    {
        return Usergroup::class;
    }
}
