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
    public function __construct(array $group, Signer $signer)
    {
        //
        $this->signer = $signer;
        $this->group = $group;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [ 'channel-'.$this->group['department'].'-'.$this->group['tab'] ];
//        return [ 'news' ];
    }

    public function broadcastWith()
    {
        return response_array('success', $this->signer);
    }
}
