<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Room;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\RoomRepositoryInterface;

class EloquentRoomRepository extends BaseEloquentRepository implements RoomRepositoryInterface
{
    public function entity()
    {
        return Room::class;
    }
}
