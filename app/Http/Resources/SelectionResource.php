<?php

namespace App\Http\Resources;
use App\Selection;
use App\Http\Resources\SelectionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectionResource extends JsonResource
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
            'race_id' => $this->race_id,
            'user_id' => $this->user_id,
            'rider_number' => $this->rider_number,
            'rider_name' => $this->rider_name,
            'rider_brand' => $this->rider_brand,
            'start_pos' => $this->start_pos,
            'finish_pos' => $this->finish_pos,
            'current_pos' => $this->current_pos,
            'user_name' => $this->user_name,
            'points' => $this->points,
        ];
    }
}
