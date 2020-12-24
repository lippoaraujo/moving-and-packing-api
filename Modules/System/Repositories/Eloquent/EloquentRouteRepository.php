<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Route;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\RouteRepositoryInterface;

class EloquentRouteRepository extends BaseEloquentRepository implements RouteRepositoryInterface
{
    public function entity()
    {
        return Route::class;
    }
}
