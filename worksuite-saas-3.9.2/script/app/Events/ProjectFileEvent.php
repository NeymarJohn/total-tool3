<?php

namespace App\Events;

use App\ProjectFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectFileEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $file;

    public function __construct(ProjectFile $file)
    {
        $this->file = $file;
    }

}
