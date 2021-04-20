<?php

namespace App\Listeners;

use App\Events\SupportTicketRequesterEvent;
use App\Notifications\NewSupportTicketRequester;
use App\Ticket;
use Illuminate\Support\Facades\Notification;

class SupportTicketAgentListener
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
     * @param  Ticket $event
     * @return void
     */
    public function handle(SupportTicketRequesterEvent $event)
    {
        if (!is_null($event->notifyUser)) {
            Notification::send($event->notifyUser, new NewSupportTicketRequester($event->ticket));
        }
    }
}
