<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class broadcastEndingInterviewEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public $group;

    public $redis_array;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, array $group, array $redis_array)
    {
        $this->message = $message;
        $this->group = $group;
        $this->redis_array = $redis_array;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [ 'channel-end-interview' ];
    }

    public function broadcastWith()
    {
        $group = $this->group;
        $redis_array = $this->redis_array;
        return response_array($this->message, compact('group', 'redis_array'));
    }
}
