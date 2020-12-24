<?php

namespace Modules\Moving\Entities\Observers;

use Illuminate\Support\Str;
use Modules\Moving\Entities\Order;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param  \Modules\Moving\Entities\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $order->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the order "updating" event.
     *
     * @param  \Modules\Moving\Entities\Order  $order
     * @return void
     */
    public function updating(Order $order)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $order->tenant_id =  $user->tenant->id;
        // }
    }
}
