<?php

namespace Modules\System\Repositories\QueryBuilder;

use App\Repositories\Core\BaseQueryBuilderRepository;
use Modules\System\Repositories\Contracts\UserRepositoryInterface;

class QueryBuilderUserRepository extends BaseQueryBuilderRepository implements UserRepositoryInterface
{
    protected $table = 'users';
}
