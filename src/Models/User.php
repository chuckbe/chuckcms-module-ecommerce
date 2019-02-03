<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Chuckbe\Chuckcms\Models\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'token'
    ];

    public function customer()
    {
        return $this->hasOne('Chuckbe\ChuckcmsModuleEcommerce\Models\Customer');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}