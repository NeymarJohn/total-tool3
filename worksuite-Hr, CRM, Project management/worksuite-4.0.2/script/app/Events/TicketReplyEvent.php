<?php

namespace App\Events;

use App\TicketReply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketReplyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketReply;
    public $notifyUser;

    public function __construct(TicketReply $ticketReply, $notifyUser)
    {
        $this->ticketReply = $ticketReply;
        $this->notifyUser = $notifyUser;
    }

}
