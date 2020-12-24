<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\User;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    public function entity()
    {
        return User::class;
    }
}
