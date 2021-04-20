<?php

namespace App\Observers;

use App\Events\NewSupportTicketEvent;
use App\Events\SupportTicketAgentEvent;
use App\Events\SupportTicketRequesterEvent;
use App\SupportTicket;
use App\User;

class SupportTicketObserver
{

    public function created(SupportTicket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {

            $users = User::allSuperAdmin();

            event(new NewSupportTicketEvent($ticket, $users));

            if($ticket->requester && user()->id != $ticket->user_id){
                event(new SupportTicketRequesterEvent($ticket, $ticket->requester));
            }
        }
    }

    public function saving(SupportTicket $ticket)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
    }

    public function updated(SupportTicket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($ticket->isDirty('agent_id')) {
                event(new SupportTicketAgentEvent($ticket, $ticket->agent));
            }
        }
    }

}
