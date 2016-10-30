<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_duties');
    }

    public function permits()
    {
        return $this->belongsToMany('App\Permit', 'duties_permits');
    }
}
