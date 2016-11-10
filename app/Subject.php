<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'groups_subjects');
    }
}
