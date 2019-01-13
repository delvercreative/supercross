<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lobby;
use App\Http\Resources\LobbyResource;

class LobbyController extends Controller
{
    public function addUser(Request $request, $id) {
        $user = $request->input('user_id');
        $lobby = Lobby::find($id);
        $lobby->users()->attach($user);
    }
}
