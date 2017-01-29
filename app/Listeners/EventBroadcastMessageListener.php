<?php

namespace App\Listeners;

use App\Events\broadcastMessageEvent;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventBroadcastMessageListener
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
     * @param  broadcastMessageEvent  $event
     * @return void
     */
    public function handle(broadcastMessageEvent $event)
    {
        //
        \Log::info($event->message, [ 'type' => $event->type ]);
    }
}
