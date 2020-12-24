<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'controllers',
        'module_id',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = ['pivot', 'created_at', 'updated_at'];

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
        return \Modules\System\Database\factories\RouteFactory::new();
    }

    public function actionsUsergroup()
    {
        return $this->belongsToMany(Action::class, 'action_route_usergroup', 'route_id', 'action_id');
    }

    public function actionsUser()
    {
        return $this->belongsToMany(Action::class, 'action_route_user', 'route_id', 'action_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'action_route_user', 'route_id', 'user_id');
    }

    public function usergroups()
    {
        return $this->belongsToMany(Usergroup::class, 'action_route_usergroup', 'route_id', 'usergroup_id');
    }
}
