<?php

namespace App\Events;

use App\RemovalRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemovalRequestApprovedRejectUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $removal;

    public function __construct(RemovalRequest $removal)
    {
        $this->removal = $removal;
    }

}
