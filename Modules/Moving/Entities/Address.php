<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Address extends Model
{
    use HasFactory, BelongsToTenants, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'locality',
        'city',
        'postcode',
        'country',
        // 'customer_id',
        'tenant_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\AddressFactory::new();
    }

    // public function Customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }
}
