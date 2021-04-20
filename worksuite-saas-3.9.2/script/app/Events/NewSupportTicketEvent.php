<?php

namespace App\Events;

use App\SupportTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSupportTicketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $notifyUser;

    public function __construct(SupportTicket $ticket, $notifyUser)
    {
        $this->ticket = $ticket;
        $this->notifyUser = $notifyUser;
    }

}
