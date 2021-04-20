<?php

namespace App\Listeners;

use App\Events\LeadEvent;
use App\Notifications\LeadAgentAssigned;
use Illuminate\Support\Facades\Notification;

class LeadListener
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
     * @param  LeadEvent $event
     * @return void
     */
    public function handle(LeadEvent $event)
    {
        if ($event->notificationName == 'LeadAgentAssigned') {
            Notification::send($event->lead->lead_agent->user, new LeadAgentAssigned($event->lead));
        }
    }
}
