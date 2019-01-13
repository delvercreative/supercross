<?php

namespace App\Listeners;

use App\Events\RaceStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RaceStatusChangedListener
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
     * @param  RaceStatusChanged  $event
     * @return void
     */
    public function handle(RaceStatusChanged $event)
    {
        //
    }
}
