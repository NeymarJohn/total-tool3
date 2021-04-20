<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $projects;
    public $data;

    public function __construct($projects, $user, $data)
    {
        $this->projects = $projects;
        $this->user = $user;
        $this->data = $data;
    }

}
