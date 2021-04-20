<?php

namespace App\Listeners;

use App\Events\TicketReplyEvent;
use App\Notifications\NewTicketReply;
use App\User;
use Illuminate\Support\Facades\Notification;

class TicketReplyListener
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
     * @param  TicketReplyEvent $event
     * @return void
     */
    public function handle(TicketReplyEvent $event)
    {
        if (!is_null($event->notifyUser)) {
            Notification::send($event->notifyUser, new NewTicketReply($event->ticketReply));
        } else {
            Notification::send(User::allAdmins(), new NewTicketReply($event->ticketReply));
        }
    }
}
