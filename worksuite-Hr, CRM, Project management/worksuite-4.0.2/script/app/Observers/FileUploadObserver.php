<?php

namespace App\Observers;

use App\Events\FileUploadEvent;
use App\ProjectFile;

class FileUploadObserver
{
    public function created(ProjectFile $file)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new FileUploadEvent($file));
        }
    }
}
