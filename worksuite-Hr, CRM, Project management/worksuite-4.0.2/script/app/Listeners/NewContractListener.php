<?php

namespace App\Listeners;

use App\Events\NewContractEvent;
use App\Notifications\NewContract;
use Illuminate\Support\Facades\Notification;

class NewContractListener
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
     * @param  NewContractEvent  $event
     * @return void
     */
    public function handle(NewContractEvent $event)
    {
        Notification::send($event->contract->client, new NewContract($event->contract));
    }
}
