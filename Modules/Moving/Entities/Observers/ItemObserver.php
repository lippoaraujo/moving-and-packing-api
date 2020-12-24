<?php

namespace Modules\Moving\Entities\Observers;

use Modules\Moving\Entities\Item;

class ItemObserver
{
    /**
     * Handle the Item "creating" event.
     *
     * @param  \Modules\Moving\Entities\Item  $item
     * @return void
     */
    public function creating(Item $item)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $item->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the item "updating" event.
     *
     * @param  \Modules\Moving\Entities\Item  $item
     * @return void
     */
    public function updating(Item $item)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $item->tenant_id =  $user->tenant->id;
        // }
    }
}
