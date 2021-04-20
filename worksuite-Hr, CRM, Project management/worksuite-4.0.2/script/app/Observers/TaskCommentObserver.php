<?php

namespace App\Observers;

use App\Events\TaskCommentEvent;
use App\Task;
use App\TaskComment;

class TaskCommentObserver
{
    public function created(TaskComment $comment)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            $task = Task::with(['project'])->findOrFail($comment->task_id);

            if ($task->project_id != null) {
                if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {
                    event(new TaskCommentEvent($task, $comment->created_at, $task->project->client, 'client'));
                }
                event(new TaskCommentEvent($task, $comment->created_at, $task->project->members_many));
            }
            else{
                event(new TaskCommentEvent($task, $comment->created_at, $task->users));
            }
        }
    }
}
