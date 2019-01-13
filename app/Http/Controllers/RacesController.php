<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Race;
use App\User;
use App\Entry;
use App\Rider;
use App\Points;
use App\Http\Resources\RiderResource;
use App\Http\Resources\RaceResource;
use App\Http\Resources\UserResource;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Events\BalanceChanged;
use Spatie\PdfToText\Pdf;




class RacesController extends Controller
{
    

    public function index()
    {
        $user = auth()->user();
        return view('races')->with('user', $user);
    }

    public function updateStatus(Request $request, Race $race)
    {
        
        $race->status = $request->status;
        $race->save();
        event(new BalanceChanged($race));
    }

    public function racersTest()
    {
        $racers = Rider::all();
        return RiderResource::collection($racers->sortBy('position'));
    }

    public function singleRace(Request $request, $id)
    {
        $race = Race::find($id);
       
        $user = auth()->user();
        $users = $race->users()->count();
        $amount = $race->wager->value;
        $winnings = $race->calcWinnings();
        return view('singlerace', compact('race', 'user', 'users', 'amount', 'winnings'));
    }

    public function updateUserCount(Request $request)
    {
        $race_id = $request->race_id;
        $race = Race::find($race_id);
        $raceUsers = $race->users()->count();
        $race->update(['count' => $raceUsers]);

    }


    public function store(Request $request, Race $race)
    {
        $session = $race->sessions()->create([
            'race_id' => $request->race_id,
            'rider_id' => $request->rider_id,
            'user_id' => Auth::id()
        ]);

        return $session->toJson();
    }

    public function loadApi()
    {
        $races = Race::all();
        return RaceResource::collection($races);
    }

    public function singleRaceApi(Request $request)
    {
        $id = $request->race_id;
        $race = Race::find($id);
        return new RaceResource($race);
    }

    public function nextRace(Request $request)
    {
        $nextRace = Race::where('status', 0)->first();
        return new RaceResource($nextRace);
    }

    public function checkUser(Request $request)
    {
        $user = User::find($request->user_id);
        return new UserResource($user);
    }

    public function addUser(Request $request) {

        $aUser = auth()->user();
        $user = $request->user_id;
        $race_id = $request->race_id;
        $race = Race::find($race_id);
        $race->users()->attach($user);
        $count = $race->users()->count();

        // ADD TO ENTRIES TABLE
        $entry = new Entry;
        $entry->user_id = $user;
        $entry->race_id = $race_id;
        $entry->amount = $race->wager->value;
        $entry->save();
        $race->update(['count' => $count]);

        // Update Balance
        $userObject = User::find($user);
        // $currentBalance = $userObject->balance;
        // $newBalance = $currentBalance + $request->amount;

        // $userObject->update(['balance' => $newBalance]);
        $userObject->updateBalance();

       
    }


    public function checkResultsPosting(Request $request)
    {
        $e = $request->e;
        $http = new \GuzzleHttp\Client;
        $url = 'https://results.amasupercross.com/xml/SX/events/'.$e.'SchedRes.xml';
        $xml = simplexml_load_file($url);
        $event = $xml->B;
        $runs = $event->C->F;

        $n = [];


        
           foreach($runs as $run) {
            $r = [];
            foreach($run->children()->R as $d){
                $r[] = $d['FN'];
            };
               $n[] = array(
                'name' => $run['CLSNAME'],
                'class_name' => $run['CLSNAME'],
                'type' => $run['TYPE'],
                'run' => $run['RNUM'],
                'data' => $r
               );
           };

        return response()->json($n);
    
    
    }

    // public function setSelection(Request $request)
    // {
    //     $race_id = $request->race_id;
    //     $status = $request->selection_status;
    //     $race = Race::find($race_id);
    //     $race->update(['allow_selection' => $status]);

    //     return response()->json('Selection allow for' .$race->name . 'at' .$race->event->name);
    // }


    public function test(Request $request)
    {
        return view('pdf');
    
    
    }


}
