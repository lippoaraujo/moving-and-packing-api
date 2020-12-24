<?php

namespace Modules\System\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Modules\System\Entities\Traits\UserACL;
use Torzer\Awesome\Landlord\BelongsToTenants;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, UserACL, BelongsToTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usergroup_id',
        'tenant_id',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\System\Database\factories\UserFactory::new();
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    public function usergroup()
    {
        return $this->belongsTo(Usergroup::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'action_route_user', 'user_id', 'route_id');
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action_route_user', 'user_id', 'action_id');
    }

    public function actionsWhere($routeId)
    {
        return $this->belongsToMany(Action::class, 'action_route_user', 'user_id', 'action_id')
                    ->withPivot('route_id')
                    ->wherePivot('route_id', $routeId)->get();
                    // ->as('action_route_user')->get();
    }

    public function attachRouteAction($routeId, $actionId)
    {
        $this->routes()->attach($routeId, ['action_id' => $actionId]);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
