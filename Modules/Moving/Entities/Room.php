<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Room extends Model
{
    use HasFactory, BelongsToTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'active',
        'tenant_id'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\RoomFactory::new();
    }

    public function images()
    {
        return $this->hasManyThrough(Image::class, OrderRoom::class);
    }

    public function orderRooms()
    {
        return $this->hasMany(OrderRoom::class);
    }
}
