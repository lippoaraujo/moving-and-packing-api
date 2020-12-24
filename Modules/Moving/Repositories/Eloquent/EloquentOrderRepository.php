<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Order;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\OrderRepositoryInterface;

class EloquentOrderRepository extends BaseEloquentRepository implements OrderRepositoryInterface
{
    public function entity()
    {
        return Order::class;
    }
}
