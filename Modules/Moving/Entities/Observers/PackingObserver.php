<?php

namespace Modules\Moving\Entities\Observers;

use Modules\Moving\Entities\Packing;

class PackingObserver
{
    /**
     * Handle the packing "creating" event.
     *
     * @param  \Modules\Moving\Entities\Packing  $packing
     * @return void
     */
    public function creating(Packing $packing)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $packing->tenant_id =  $user->tenant->id;
        }
    }
}
