<?php

namespace App\Listeners;

use App\Events\SupportTicketReplyEvent;
use App\Events\TicketReplyEvent;
use App\Notifications\NewSupportTicketReply;
use App\User;
use Illuminate\Support\Facades\Notification;

class SupportTicketReplyListener
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
    public function handle(SupportTicketReplyEvent $event)
    {
        if (!is_null($event->notifyUser)) {
            Notification::send($event->notifyUser, new NewSupportTicketReply($event->ticketReply));
        } else {
            Notification::send(User::allSuperAdmin(), new NewSupportTicketReply($event->ticketReply));
        }
    }
}
