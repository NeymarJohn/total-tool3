<?php

namespace App\Observers;

use App\Events\SubTaskCompletedEvent;
use App\SubTask;

class SubTaskObserver
{
    public function created(SubTask $subTask)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new SubTaskCompletedEvent($subTask, 'created'));
        }
    }

    public function updated(SubTask $subTask)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if($subTask->isDirty('status') && $subTask->status == 'complete'){
                event(new SubTaskCompletedEvent($subTask, 'completed'));
            }
        }
    }
}
