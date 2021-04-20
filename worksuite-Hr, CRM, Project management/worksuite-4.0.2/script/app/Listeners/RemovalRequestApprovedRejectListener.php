<?php

namespace App\Listeners;

use App\Events\RemovalRequestApprovedRejectUserEvent;
use App\Events\RemovalRequestApproveRejectEvent;
use App\Notifications\RemovalRequestApprovedReject;
use App\Notifications\RemovalRequestApprovedRejectUser;
use Illuminate\Support\Facades\Notification;

class RemovalRequestApprovedRejectListener
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
    public function handle(RemovalRequestApproveRejectEvent $event)
    {
        Notification::send($event->removalRequest->user, new RemovalRequestApprovedReject($event->removalRequest->status));
    }
}
