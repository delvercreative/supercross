<?php

namespace App;

use App\Selection;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    protected $fillable = [
        'race_id', 'points', 'user_id', 'rider_number', 'rider_name', 'rider_brand', 'start_pos', 'finish_pos', 'user_name', 'current_pos'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function race()
    {
        return $this->belongsTo('App\Race');
    }

    public function pointTotal()
    {
        $userSelections = Selection::where('user_id', $this->user->id)->value('points');
        $uSelections = [];
        foreach($userSelections as $selection){
            $uSelections[] = $selection;
        }
        return $uSelections;
    }
}

