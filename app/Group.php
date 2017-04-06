<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'groups_users');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'groups_subjects');
    }


}
