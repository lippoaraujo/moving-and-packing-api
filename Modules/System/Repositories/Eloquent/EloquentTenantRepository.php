<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Tenant;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\TenantRepositoryInterface;

class EloquentTenantRepository extends BaseEloquentRepository implements TenantRepositoryInterface
{
    public function entity()
    {
        return Tenant::class;
    }
}
