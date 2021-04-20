<?php

namespace App\Listeners;

use App\Events\TicketRequesterEvent;
use App\Notifications\NewTicketRequester;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Notification;

class TicketRequesterListener
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
    public function handle(TicketRequesterEvent $event)
    {
        if (!is_null($event->notifyUser)) {
            Notification::send($event->notifyUser, new NewTicketRequester($event->ticket));
        }
    }
}
