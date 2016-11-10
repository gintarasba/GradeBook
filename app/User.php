<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'second_name', 'loginName', 'pcode', 'level', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function duties()
    {
        return $this->belongsToMany('App\Duty', 'users_duties');
    }

    public function group()
    {
        return $this->belongsToMany('App\Group', 'groups_users');
    }

}
