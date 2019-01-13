<?php

namespace App\Listeners;

use App\Events\BalanceChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BalanceChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BalanceChanged  $event
     * @return void
     */
    public function handle(BalanceChanged $event)
    {
        //
    }
}
