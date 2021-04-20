<?php

namespace App\Observers;

use App\Events\TicketEvent;
use App\Events\TicketRequesterEvent;
use App\Ticket;
use App\UniversalSearch;

class TicketObserver
{
    public function created(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //send admin notification
            event(new TicketEvent($ticket, 'NewTicket'));

            if($ticket->requester){
                event(new TicketRequesterEvent($ticket, $ticket->requester));
            }
        }
    }

    public function updated(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($ticket->isDirty('agent_id')) {
                event(new TicketEvent($ticket, 'TicketAgent'));
            }    
        }
    }

    public function deleting(Ticket $ticket)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $ticket->id)->where('module_type', 'ticket')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }
}
