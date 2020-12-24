<?php

namespace Modules\System\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'cnpj',
        'name',
        'trading_name',
        'email',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\System\Database\factories\TenantFactory::new();
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Usergroup::class);
    }

    public function usergroups()
    {
        return $this->hasMany(Usergroup::class);
    }

    // public function getUsergroupsOfTenant(string $id)
    // {
    //     return $this->findOrFail($id)->usergroups()->get();
    // }
}
