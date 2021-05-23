<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Item extends Model
{
    use HasFactory, BelongsToTenants, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_pt',
        'name_es',
        'description',
        'cubic_feet',
        'packing_qty',
        'packing_id',
        'tag',
        'tenant_id'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\ItemFactory::new();
    }

    public function orderRooms()
    {
        return $this->belongsToMany(OrderRoom::class, 'item_order_room', 'item_id', 'order_room_id');
    }

    public function packing()
    {
        return $this->hasOne(Packing::class, 'id', 'packing_id');
    }
}
