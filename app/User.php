<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'earnings', 'balance', 'stripe_id', 'display_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function selections()
    {
        return $this->hasMany('App\Selection');
    }

    public function results()
    {
        return $this->hasMany('App\Result');
    }

    public function deposits()
    {
        return $this->hasMany('App\Deposit');
    }

    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    public function payouts()
    {
        return $this->hasMany('App\Payout');
    }

    public function races()
    {
        return $this->belongsToMany('App\Race', 'race_user');
    }
    

    //  public function getChargesAttribute()
    // {
    //     $charges = Stripe::charges()->all(['customer' => $this->stripe_id]);
    //     return $charges;
    // }

    public function updateBalance()
    {
        $balance = $this->balance;

        $entries = $this->entries;
        $deposits = $this->deposits;
        $payouts = $this->payouts;
        $winnings = $this->results;
        $plus = [];
        $minus = [];
        $d = [];
        $p = [];
        $w = [];
        foreach ($entries as $entry) {
            $minus[] = $entry->amount;
        }
        foreach ($deposits as $deposit) {
            $plus[] = $deposit->amount;
        }
        foreach ($payouts as $payout) {
            $minus[] = $payout->amount;
        }
        foreach ($winnings as $win) {
            $plus[] = $win->earnings;
        }
        $plusTotal = array_sum($plus);
        $minusTotal = array_sum($minus);
        $newBalance = $plusTotal - $minusTotal;

        $this->update(['balance' => $newBalance]);

    }
}
