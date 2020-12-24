<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Usergroup extends Model
{
    use HasFactory, BelongsToTenants;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tenant_id',
        'active',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\System\Database\factories\UsergroupFactory::new();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'action_route_usergroup', 'usergroup_id', 'route_id');
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action_route_usergroup', 'usergroup_id', 'action_id');
    }

    public function actionsWhere($routeId)
    {
        return $this->belongsToMany(Action::class, 'action_route_usergroup', 'usergroup_id', 'action_id')
                    ->withPivot(['route_id', 'is_menu'])
                    ->wherePivot('route_id', $routeId)->get();
                    // ->as('action_route_usergroup')->get();
    }

    public function attachRouteAction($routeId, $actionId)
    {
        $this->routes()->attach($routeId, ['action_id' => $actionId]);
    }
}
