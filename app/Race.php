<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{

    protected $fillable = [
        'name', 'event_id', 'level_id', 'status', 'wager_id', 'max_entries', 'count'
    ];

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function selections()
    {
        return $this->hasMany('App\Selection');
    }

    public function results()
    {
        return $this->hasMany('App\Result');
    }

    public function wager()
    {
        return $this->belongsTo('App\Wager');
    }

    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'race_user');
    }

    public function calcWinnings()
    {
        $count = count($this->users);
        $wins = [];
        $entries = $this->entries;
        $entryVals = [];
        foreach($entries as $entry){
            $entryVals[] = $entry->amount;
        }
        $totalEntryAmt = array_sum($entryVals);
        if($count < 4){
            $wins[] = array('place' => 1, 'amount' => $totalEntryAmt - 2);
        }
        if($count > 3 & $count < 6){
            $third = $this->wager->value/2;
            $calc_1 = $totalEntryAmt - $third;
            $second = ($calc_1 * 0.3) - 1;
            $first = ($calc_1 * 0.7) - 1;
            $wins[] = array('place' => 1, 'amount' => $first);
            $wins[] = array('place' => 2, 'amount' => $second);
            $wins[] = array('place' => 3, 'amount' => $third);
        }
        if($count > 5) {
            $third = $this->wager->value;
            $calc_2 = $totalEntryAmt - $third;
            $second = ($calc_2 * .65) - 1;
            $first = ($calc_2 * .35) - 1;
            $wins[] = array('place' => 1, 'amount' => $first);
            $wins[] = array('place' => 2, 'amount' => $second);
            $wins[] = array('place' => 3, 'amount' => $third);
        }

        return $wins;
    }

    
}
