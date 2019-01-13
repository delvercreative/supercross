<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id', 'race_id', 'points', 'earnings'
    ];

    public function race()
    {
        return $this->belongsTo('App\Race');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
