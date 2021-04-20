<?php

namespace App\Events;

use App\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketRequesterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $notifyUser;

    public function __construct(Ticket $ticket, $notifyUser)
    {
        $this->ticket = $ticket;
        $this->notifyUser = $notifyUser;
    }

}
