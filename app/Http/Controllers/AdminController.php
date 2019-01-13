<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Race;
use App\Event;
use App\Events\BalanceChanged;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $next = Race::where('status', 0)->first();
 
        $races = Race::all();
        return view('admin.dashboard', compact('races', 'next'));
    }

    public function updateStatus(Request $request, Race $race)
    {
        
        $s = $request->input('race_status');
        $race->status = $s;
        $race->save();
        event(new BalanceChanged($race));
        return back()->with('message', 'Race Status updated successfully!');
       
    }
}