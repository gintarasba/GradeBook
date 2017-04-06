<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    public function user()
    {
        $this->belongsTo('App\User');
    }
}
