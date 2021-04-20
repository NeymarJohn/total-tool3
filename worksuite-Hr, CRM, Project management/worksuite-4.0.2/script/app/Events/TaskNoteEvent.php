<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskNoteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $notifyUser;
    public $created_at;
    public $client;

    public function __construct(Task $task, $created_at, $notifyUser, $client = null)
    {
        $this->task = $task;
        $this->created_at = $created_at;
        $this->notifyUser = $notifyUser;
        $this->client = $client;
    }

}
