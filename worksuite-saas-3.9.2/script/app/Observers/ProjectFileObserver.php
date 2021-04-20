<?php

namespace App\Observers;


use App\Events\ProjectFileEvent;
use App\ProjectFile;

class ProjectFileObserver
{

    public function created(ProjectFile $file)
    {
        if (!isRunningInConsoleOrSeeding()) {
            event(new ProjectFileEvent($file));
        }
    }

    public function saving(ProjectFile $file)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $file->company_id = company()->id;
        }
    }

}
