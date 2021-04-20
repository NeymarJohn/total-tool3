<?php

namespace App\Listeners;

use App\Events\RemovalRequestApprovedRejectUserEvent;
use App\Notifications\RemovalRequestApprovedRejectUser;
use Illuminate\Support\Facades\Notification;

class RemovalRequestApprovedRejectUserListener
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
     * @param  RemovalRequestApprovedRejectUserEvent $event
     * @return void
     */
    public function handle(RemovalRequestApprovedRejectUserEvent $event)
    {
        Notification::send($event->removal->user, new RemovalRequestApprovedRejectUser($event->removal->status));
    }
}
