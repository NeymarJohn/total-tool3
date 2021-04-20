<?php

namespace App\Listeners;

use App\Events\RemovalRequestApprovedRejectLeadEvent;
use App\Notifications\RemovalRequestApprovedRejectLead;
use App\User;
use Illuminate\Support\Facades\Notification;

class RemovalRequestApprovedRejectLeadListener
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
     * @param  RemovalRequestApprovedRejectLeadEvent $event
     * @return void
     */
    public function handle(RemovalRequestApprovedRejectLeadEvent $event)
    {
        Notification::send($event->removal->lead, new RemovalRequestApprovedRejectLead($event->removal->status));
    }
}
