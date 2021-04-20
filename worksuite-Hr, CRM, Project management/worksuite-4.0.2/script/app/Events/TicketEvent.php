<?php

namespace App\Events;

use App\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $notificationName;

    public function __construct(Ticket $ticket, $notificationName)
    {
        $this->ticket = $ticket;
        $this->notificationName = $notificationName;
    }

}
