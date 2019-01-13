<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'date', 'round', 'status', 'is_triplecrown'
    ];
    public function races()
    {
        return $this->hasMany('App\Race');
    }
}
