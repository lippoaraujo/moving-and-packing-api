<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'parent_module',
        'stripe_plan_id',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * A plan may be given various modules.
     */
    public function modules()
    {
        return $this->belongsToMany(
            Module::class,
            'plan_has_modules'
        );
    }
}
