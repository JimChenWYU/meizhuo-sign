<?php

namespace App\Listeners;

use App\Events\broadcastSignerEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventBroadcastSignerListener
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
     * @param  broadcastSignerEvent  $event
     * @return void
     */
    public function handle(broadcastSignerEvent $event)
    {
        //
    }
}
