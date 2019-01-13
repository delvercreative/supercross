<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    public function race()
    {
        return $this->hasOne('App\Race');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
