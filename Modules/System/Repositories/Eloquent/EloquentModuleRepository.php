<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Module;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\ModuleRepositoryInterface;

class EloquentModuleRepository extends BaseEloquentRepository implements ModuleRepositoryInterface
{
    public function entity()
    {
        return Module::class;
    }
}
