<?php

namespace App\Events;

use App\Issue;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewIssueEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

}
