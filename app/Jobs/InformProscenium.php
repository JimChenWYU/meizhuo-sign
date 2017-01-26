<?php

namespace App\Jobs;

use App\Events\broadcastEndingInterviewEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class InformProscenium extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $group;

    protected $redis_array;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $group, array $redis_array)
    {
        //
        $this->group = $group;
        $this->redis_array = $redis_array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cache_signer = unserialize(redis()->lindex($this->group['department'], 0));

        if (isset($cache_signer) && $cache_signer['status'] === 1) {
            $cache_signer['status'] = 2;
            redis()->lset($this->group['department'], 0, serialize($cache_signer));

            $message = $this->group['department'].'第'.$this->group['tab'].'组面试完毕！';
            \Event::fire(
                new broadcastEndingInterviewEvent($message, $this->redis_array));
        }
    }
}
