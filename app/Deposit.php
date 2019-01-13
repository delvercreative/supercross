<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id', 'transaction_id', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
