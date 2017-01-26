<?php

namespace App\Listeners;

use App\Events\broadcastEndingInterviewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventBroadcastEndingInterviewListener
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
     * @param  broadcastEndingInterviewEvent  $event
     * @return void
     */
    public function handle(broadcastEndingInterviewEvent $event)
    {
        //
        $redis_signer = $event->redis_array;
        \Log::info('广播消息：'.$event->message, compact('redis_signer'));
    }
}
