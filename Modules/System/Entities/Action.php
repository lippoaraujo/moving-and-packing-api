<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Action extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = ['pivot','created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\System\Database\factories\ActionFactory::new();
    }

    public function routesUsergroup()
    {
        return $this->belongsToMany(Route::class, 'action_route_usergroup', 'action_id', 'route_id')
                    ->withPivot('usergroup_id');
    }

    public function routesUser()
    {
        return $this->belongsToMany(Route::class, 'action_route_user', 'action_id', 'route_id')
                    ->withPivot('user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'action_route_user', 'action_id', 'user_id');
    }

    public function usergroups()
    {
        return $this->belongsToMany(Usergroup::class, 'action_route_usergroup', 'action_id', 'usergroup_id');
    }
}
