<?php

namespace Modules\System\Entities\Observers;

use Illuminate\Support\Str;
use Modules\system\Entities\Tenant;

class TenantObserver
{
    /**
     * Handle the tenant "creating" event.
     *
     * @param  \Modules\system\Entities\Tenant  $tenant
     * @return void
     */
    public function creating(Tenant $tenant)
    {
        if(empty($tenant->trading_name)) {
            $tenant->trading_name = $tenant->name;
        }

        $tenant->uuid = Str::uuid();
    }

    /**
     * Handle the tenant "updating" event.
     *
     * @param  \Modules\system\Entities\Tenant  $tenant
     * @return void
     */
    public function updating(Tenant $tenant)
    {
        if(empty($tenant->trading_name)) {
            $tenant->trading_name = $tenant->name;
        }
    }
}
