<?php

namespace Modules\Moving\Entities\Observers;

use Illuminate\Support\Str;
use Modules\Moving\Entities\Address;
use Tymon\JWTAuth\Claims\Custom;

class AddressObserver
{
    /**
     * Handle the address "creating" event.
     *
     * @param  \Modules\Moving\Entities\Address  $address
     * @return void
     */
    public function creating(Address $address)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $address->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the address "updating" event.
     *
     * @param  \Modules\Moving\Entities\Address  $address
     * @return void
     */
    public function updating(Address $address)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $address->tenant_id =  $user->tenant->id;
        // }
    }
}
