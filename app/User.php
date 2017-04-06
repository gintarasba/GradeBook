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
        'uid', 'name', 'second_name', 'loginName', 'pcode', 'level', 'password',
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

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'users_subjects');
    }

    public function photos()
    {
        return $this->hasMany('App\UserPhoto');
    }

    public function marks()
    {
        return $this->hasMany('App\Mark');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Conversation', 'users_conversations');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function isAdmin()
    {
        if ($this->level == 2) {
            return true;
        }
        return false;
    }
}
