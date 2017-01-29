<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class broadcastMessageEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public $data;

    public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, array $data = [], $type = 'interview')
    {
        //
        $this->message = $message;
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        $channel = 'channel-message-'.$this->type;
        return [ $channel ];
    }

    public function broadcastWith()
    {
        return response_array($this->message, $this->data);
    }
}
