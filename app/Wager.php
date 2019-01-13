<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wager extends Model
{
    public function races()
    {
        return $this->hasMany('App\Race');
    }
}
