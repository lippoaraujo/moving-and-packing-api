<?php

namespace Modules\Moving\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Image extends Model
{
    use HasFactory, BelongsToTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'order_room_id',
        'tenant_id'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Moving\Database\factories\ImageFactory::new();
    }

    public function orderRoom()
    {
        return $this->belongsTo(OrderRoom::class, 'order_room_id');
    }
}
