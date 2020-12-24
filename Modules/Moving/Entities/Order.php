<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, BelongsToTenants;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    // const CREATED_AT = 'ordered_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_signature',
        'price',
        'address_id',
        'customer_id',
        'user_id',
        'expected_date',
        'tenant_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\OrderFactory::new();
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(\Modules\System\Entities\User::class, 'id', 'user_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'order_room', 'order_id', 'room_id')
                    ->withTimestamps()
                    ->withPivot(['obs', 'id']);
    }

    public function images()
    {
        return $this->hasManyThrough(Image::class, OrderRoom::class);
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, OrderRoom::class);
    }

    public function orderRooms()
    {
        return $this->hasMany(OrderRoom::class);
    }
}
