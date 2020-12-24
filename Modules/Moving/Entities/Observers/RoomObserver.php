<?php

namespace Modules\Moving\Entities\Observers;

use Modules\Moving\Entities\Room;

class RoomObserver
{
    /**
     * Handle the room "creating" event.
     *
     * @param  \Modules\Moving\Entities\Room  $room
     * @return void
     */
    public function creating(Room $room)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $room->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the room "updating" event.
     *
     * @param  \Modules\Moving\Entities\Room  $room
     * @return void
     */
    public function updating(Room $room)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $room->tenant_id =  $user->tenant->id;
        // }
    }
}
