<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Entry;
use App\Deposit;
use App\Payout;
use App\Http\Resources\EntryResource;
use App\Http\Resources\DepositResource;
use App\Http\Resources\PayoutResource;

class UserResource extends JsonResource
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
            'display_name' => $this->display_name,
            'email' => $this->email,
            'earnings' => $this->earnings,
            'balance' => $this->balance,
            'stripe_id' => $this->stripe_id,
            'entries' => $this->entries,
            'deposits' => $this->deposits,
            'payouts' => $this->payouts,
            'races' => $this->races,
        ];
    }
}
