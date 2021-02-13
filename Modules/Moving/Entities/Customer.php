<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Customer extends Model
{
    use HasFactory, BelongsToTenants, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'primary_address_id',
        'tenant_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\CustomerFactory::new();
    }

    public function primaryAddress()
    {
        return $this->hasOne(Address::class, 'id', 'primary_address_id');
    }

    public function setprimaryAddress(Address $address)
    {
        return $this->primary_address_id = $address->id;
    }

    // public function adresses()
    // {
    //     return $this->hasMany(Address::class, 'customer_id', 'id');
    // }

    public function isPrimaryAddress(Address $address)
    {
        return $this->primary_address_id === $address->id;
    }
}
