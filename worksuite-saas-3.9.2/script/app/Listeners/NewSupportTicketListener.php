<?php

namespace App\Listeners;

use App\Events\NewSupportTicketEvent;
use App\Notifications\NewSupportTicket;
use App\Ticket;
use Illuminate\Support\Facades\Notification;

class NewSupportTicketListener
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
    public function handle(NewSupportTicketEvent $event)
    {
        if (!is_null($event->notifyUser)) {
            Notification::send($event->notifyUser, new NewSupportTicket($event->ticket));
        }
    }
}
