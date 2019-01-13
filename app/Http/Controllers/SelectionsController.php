<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Selection;
use App\Race;
use App\Events\BalanceChanged;
use App\Http\Resources\SelectionResource;

class SelectionsController extends Controller
{
    public function create(Request $request)
    {
        foreach($request->data as $data){

            $selection = new Selection([
                'user_id' => $data['user_id'],
                'user_name' => $data['user_name'],
                'race_id' => $data['race_id'],
                'rider_number' => $data['rider_number'],
                'rider_name' => $data['rider_name'],
                'rider_brand' => $data['rider_brand'],
                'start_pos' => $data['start_pos'],
                'current_pos' => $data['current_pos'],
                'points' => $data['points']
            ]);
            $selection->save();
            
        }

        $raceId = $request->race;
        $raceStatus = Race::find($raceId);
        $raceStatus->status = 1;
        $raceStatus->save();
        event(new BalanceChanged($raceStatus));
        return response()->json('Successfully generated selections for '.$raceStatus->name.'at '. $raceStatus->event->name);


    }

    public function update(Request $request)
    {
        foreach($request->data as $data){
            $selection = Selection::find($data['id']);
            $selection->finish_pos = $data['current_pos'];
            $selection->points = $data['points'];

            $selection->save();
        }
        return response()->json('Updated successfully');
    }

    public function userTotals(Request $request, User $user)
    {
        
        $userSelections = Selection::where('user_id', $user)->value('points');
        
        return new SelectionResource($userSelections);
    }
}
