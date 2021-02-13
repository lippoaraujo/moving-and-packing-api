<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderRoom extends Model
{
    // use HasFactory;

    protected $table = 'order_room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'room_id',
        'obs',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // protected static function newFactory()
    // {
    //     return \Modules\Moving\Database\factories\OrderRoomFactory::new();
    // }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_order_room', 'order_room_id', 'item_id')
                    ->withTimestamps()
                    ->withPivot(['obs', 'quantity']);
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
