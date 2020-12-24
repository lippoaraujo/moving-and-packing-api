<?php

namespace Modules\Moving\Entities\Observers;

use Illuminate\Support\Str;
use Modules\Moving\Entities\Image;

class ImageObserver
{
    /**
     * Handle the image "creating" event.
     *
     * @param  \Modules\Moving\Entities\Image  $image
     * @return void
     */
    public function creating(Image $image)
    {
        if(auth('api')->check()) {
            $user = auth('api')->user();
            $image->tenant_id =  $user->tenant->id;
        }
    }

    /**
     * Handle the image "updating" event.
     *
     * @param  \Modules\Moving\Entities\Image  $image
     * @return void
     */
    public function updating(Image $image)
    {
        // if(auth('api')->check()) {
        //     $user = auth('api')->user();
        //     $image->tenant_id =  $user->tenant->id;
        // }
    }
}
