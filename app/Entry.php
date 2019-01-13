<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{

    protected $fillable = [
        'user_id', 'race_id', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function race()
    {
        return $this->belongsTo('App\Race');
    }
}
