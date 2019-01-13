<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }
}
