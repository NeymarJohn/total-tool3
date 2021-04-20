<?php

namespace App\Listeners;

use App\Events\RemovalRequestAdminEvent;
use App\Notifications\RemovalRequestAdminNotification;
use App\User;
use Illuminate\Support\Facades\Notification;

class RemovalRequestAdminListener
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
     * @param  RemovalRequestAdminEvent $event
     * @return void
     */
    public function handle(RemovalRequestAdminEvent $event)
    {

        Notification::send(User::allAdmins(), new RemovalRequestAdminNotification());
    }
}
