<?php

namespace App\Listeners;

use App\Events\RemovalRequestAdminLeadEvent;
use App\Notifications\RemovalRequestAdminNotification;
use App\User;
use Illuminate\Support\Facades\Notification;

class RemovalRequestAdminLeadListener
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
     * @param  RemovalRequestAdminLeadEvent $event
     * @return void
     */
    public function handle(RemovalRequestAdminLeadEvent $event)
    {

        Notification::send(User::allAdmins(), new RemovalRequestAdminNotification());
    }
}
