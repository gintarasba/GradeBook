<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    public function duties()
    {
        $this->belongsToMany('App\Duty', 'duties_permits');
    }
}
