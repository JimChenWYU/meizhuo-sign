<?php

namespace App\Jobs;

use App\Events\broadcastEndingInterviewEvent;
use App\Events\broadcastMessageEvent;
use App\Http\Controllers\ManagerController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class InformProscenium extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $group;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $group)
    {
        //
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ManagerController $ctrl)
    {
        $status = $ctrl::$STATUE;

        $cache_signer = unserialize(redis()->lindex($this->group['department'], 0));

        if ($cache_signer && $cache_signer['status'] === $status['等待中']) {
//            dd($redis_array);
            $cache_signer['status'] = 2;
            redis()->lset($this->group['department'], 0, serialize($cache_signer));

            $message = $this->group['department'].'第'.$this->group['tab'].'组面试完毕！';
            \Event::fire(new broadcastEndingInterviewEvent($message, $ctrl->getQueueArray()));
            \Event::fire(new broadcastMessageEvent('请稍等...'));
        } elseif (! $cache_signer){
            $message = $this->group['department'].'已经没有人来面试！';
            \Event::fire(new broadcastMessageEvent($message));
        } else {
            \Event::fire(new broadcastMessageEvent('请稍等...'));
        }
    }
}
