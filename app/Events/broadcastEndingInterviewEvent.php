<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class broadcastEndingInterviewEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    protected $message;

    protected $redis_array;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, array $redis_array)
    {
        $this->message = $message;
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
        return response_array($this->message, $this->redis_array);
    }
}
