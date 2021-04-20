<?php

namespace App\Listeners;

use App\Events\EstimateDeclinedEvent;
use App\Notifications\EstimateDeclined;
use App\User;
use Illuminate\Support\Facades\Notification;

class EstimateDeclinedListener
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
     * @param  EstimateDeclinedEvent  $event
     * @return void
     */
    public function handle(EstimateDeclinedEvent $event)
    {
        Notification::send(User::allAdmins(), new EstimateDeclined($event->estimate));
    }
}
