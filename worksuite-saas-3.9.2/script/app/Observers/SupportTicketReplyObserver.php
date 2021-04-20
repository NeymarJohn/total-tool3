<?php

namespace App\Observers;

use App\Events\SupportTicketReplyEvent;
use App\SupportTicketReply;

class SupportTicketReplyObserver
{

    public function saving(SupportTicketReply $ticket)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
    }
    public function created(SupportTicketReply $ticketReply)
    {
        $ticketReply->ticket->touch();
        if (!isRunningInConsoleOrSeeding()) {
            if (count($ticketReply->ticket->reply) > 1) {
                if (!is_null($ticketReply->ticket->agent) && user()->id != $ticketReply->ticket->agent_id  && user()->id == $ticketReply->ticket->user_id) {
                    event(new SupportTicketReplyEvent($ticketReply, $ticketReply->ticket->agent));
                }
                else if (is_null($ticketReply->ticket->agent) && user()->id == $ticketReply->ticket->user_id) {
                    event(new SupportTicketReplyEvent($ticketReply, null));
                }
                else{
                    event(new SupportTicketReplyEvent($ticketReply, $ticketReply->ticket->requester));
                }
            }
        }
    }
}
