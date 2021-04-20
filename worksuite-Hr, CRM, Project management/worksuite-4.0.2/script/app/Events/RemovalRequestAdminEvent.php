<?php

namespace App\Events;

use App\RemovalRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemovalRequestAdminEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $removalRequest;

    public function __construct(RemovalRequest $removalRequest)
    {
        $this->removalRequest = $removalRequest;
    }

}
