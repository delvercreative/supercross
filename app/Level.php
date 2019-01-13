<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function riders()
    {
        return $this->hasMany('App\Rider');
    }

     public function getImageAttribute()
    {
       $image = asset('img/'. $this->name .'main.png');
       return $image;
    }
}
