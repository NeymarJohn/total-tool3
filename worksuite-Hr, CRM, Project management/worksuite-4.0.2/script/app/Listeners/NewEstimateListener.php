<?php

namespace App\Listeners;

use App\Events\NewEstimateEvent;
use App\Notifications\NewEstimate;
use Illuminate\Support\Facades\Notification;

class NewEstimateListener
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
     * @param  NewEstimateEvent  $event
     * @return void
     */
    public function handle(NewEstimateEvent $event)
    {
        Notification::send($event->estimate->client, new NewEstimate($event->estimate));
    }
}
