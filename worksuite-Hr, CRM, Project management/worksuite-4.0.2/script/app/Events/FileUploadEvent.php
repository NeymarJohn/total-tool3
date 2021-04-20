<?php

namespace App\Events;

use App\ProjectFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fileUpload;

    public function __construct(ProjectFile $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

}
