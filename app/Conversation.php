<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_conversations');
    }

    public function messages()
    {
        return $this->hasMany('App\UserMessage');
    }
}
