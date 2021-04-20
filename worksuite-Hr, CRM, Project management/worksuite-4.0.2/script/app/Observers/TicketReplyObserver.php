<?php

namespace App\Observers;

use App\Events\TicketReplyEvent;
use App\TicketReply;
use Illuminate\Support\Facades\Auth;

class TicketReplyObserver
{
    public function created(TicketReply $ticketReply)
    {
        $ticketReply->ticket->touch();

        if (!isRunningInConsoleOrSeeding()) {
            if (count($ticketReply->ticket->reply) > 1) {
                if (!is_null($ticketReply->ticket->agent && user()->id != $ticketReply->ticket->agent_id)) {
                    event(new TicketReplyEvent($ticketReply, $ticketReply->ticket->agent));
                } else if (is_null($ticketReply->ticket->agent)) {
                    event(new TicketReplyEvent($ticketReply, null));
                } else {
                    event(new TicketReplyEvent($ticketReply, $ticketReply->ticket->client));
                }
            }
        }
    }
}
