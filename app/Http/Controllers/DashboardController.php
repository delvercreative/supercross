<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Race;
use App\Level;
use App\Http\Resources\RaceResource;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $race = Race::where('status', 0)->first();
        $class = $race->level->image;
        return view('userdashboard.index')->with('next', $race)->with('logo', $class);
    }

    // public function showUserProfile()
    // {
    //     $user = auth()->user();
    //     return view('userdashboard.profile')->with('user', $user);
    // }
}
