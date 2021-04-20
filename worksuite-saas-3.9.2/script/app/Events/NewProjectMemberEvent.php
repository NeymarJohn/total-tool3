<?php

namespace App\Events;

use App\ProjectMember;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProjectMemberEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectMember;

    public function __construct(ProjectMember $projectMember)
    {
        $this->projectMember = $projectMember;
    }

}
