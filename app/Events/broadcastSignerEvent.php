<?php

namespace App\Events;

use App\Models\Signer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class broadcastSignerEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $signer;
    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $group, array $signer)
    {
        //
        $this->group = $group;
        $this->signer = $signer;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [ 'channel-'.$this->group['department'] ];
//        return [ 'news' ];
    }

    public function broadcastWith()
    {
        return response_array('success', $this->signer);
    }
}
