<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Packing;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\PackingRepositoryInterface;

class EloquentPackingRepository extends BaseEloquentRepository implements PackingRepositoryInterface
{
    public function entity()
    {
        return Packing::class;
    }
}
