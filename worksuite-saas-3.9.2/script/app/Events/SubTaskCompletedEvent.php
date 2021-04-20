<?php

namespace App\Events;

use App\SubTask;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubTaskCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subTask;

    public function __construct(SubTask $subTask, $status)
    {
        $this->subTask = $subTask;
        $this->status = $status;
    }

}
