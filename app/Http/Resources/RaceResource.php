<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Event;
use App\Level;
use App\Wager;
use App\Lobby;
use App\Selection;
use App\Points;
use App\Http\Resources\PointsResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\WagerResource;
use App\Http\Resources\LobbyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SelectionResource;

class RaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'event' => $this->event,
            'class' => new LevelResource(Level::findOrFail($this->level_id)),
            'wager' => new WagerResource(Wager::findOrFail($this->wager_id)),
            'max_entries' => $this->max_entries,
            'status' => $this->status,
            'users' => UserResource::collection($this->users),
            'selections' => SelectionResource::collection($this->selections->sortBy('current_pos')),
            'winnings' => $this->calcWinnings(),
            'points' => PointsResource::collection(Points::all())
        ];
    }
}
