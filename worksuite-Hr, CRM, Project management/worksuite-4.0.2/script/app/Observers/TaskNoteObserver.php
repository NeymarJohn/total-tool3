<?php

namespace App\Observers;

use App\Events\TaskNoteEvent;
use App\Task;
use App\TaskNote;

class TaskNoteObserver
{
    public function created(TaskNote $note)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            $task = Task::with(['project'])->findOrFail($note->task_id);

            if ($task->project_id != null) {
                if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {
                    event(new TaskNoteEvent($task, $note->created_at, $task->project->client, 'client'));
                }
                event(new TaskNoteEvent($task, $note->created_at, $task->project->members_many));
            }
            else{
                event(new TaskNoteEvent($task, $note->created_at, $task->users));
            }
        }
    }
}
