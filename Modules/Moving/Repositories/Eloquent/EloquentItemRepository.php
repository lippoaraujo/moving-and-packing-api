<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Item;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\ItemRepositoryInterface;

class EloquentItemRepository extends BaseEloquentRepository implements ItemRepositoryInterface
{
    public function entity()
    {
        return Item::class;
    }
}
