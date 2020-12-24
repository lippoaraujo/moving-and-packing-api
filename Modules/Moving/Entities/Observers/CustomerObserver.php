<?php

namespace Modules\Moving\Entities\Observers;

use Illuminate\Support\Str;
use Modules\Moving\Entities\Address;
use Modules\Moving\Entities\Customer;
use Tymon\JWTAuth\Claims\Custom;

class CustomerObserver
{
    /**
     * Handle the customer "creating" event.
     *
     * @param  \Modules\Moving\Entities\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $customer->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the customer "updating" event.
     *
     * @param  \Modules\Moving\Entities\Customer  $customer
     * @return void
     */
    public function updating(Customer $customer)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $customer->tenant_id =  $user->tenant->id;
        // }
    }
}
