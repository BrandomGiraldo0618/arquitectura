<?php

namespace App\Listeners;

use App\Events\NewUserEvent;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailToUser implements ShouldQueue
{

    use InteractsWithQueue;

     /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 2;


    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'database';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 30;


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
     * @param  NewUserEvent  $event
     * @return void
     */
    public function handle(NewUserEvent $event)
    {

    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  \App\Events\NewUserEvent  $event
     * @return bool
     */
    public function shouldQueue(NewUserEvent $event)
    {
        // Metodo por si necesitamos que se dispare el evento validando algo
        // \Log::info(self::class);

        return true;
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\NewUserEvent  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(NewUserEvent $event, $exception)
    {
        // usually would send new notification to admin/user
        info($exception);
    }

    /**
     * Determine the time at which the listener should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addSeconds(5);
    }


}
