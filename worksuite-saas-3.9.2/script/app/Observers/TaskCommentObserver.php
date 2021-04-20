<?php

namespace App\Observers;

use App\Notifications\TaskCommentClient;
use App\Task;
use App\TaskComment;
use App\User;
use Illuminate\Support\Facades\Notification;

class TaskCommentObserver
{
    public function created(TaskComment $comment)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $task = Task::with(['project', 'project.members'])->findOrFail($comment->task_id);

            $notifyUser = User::findOrFail($comment->user_id);
            $notifyUser->notify(new \App\Notifications\TaskComment($task, $comment->created_at));

            if ($task->project_id != null) {
                if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {
                    $task->project->client->notify(new TaskCommentClient($task, $comment->created_at));
                }

                $members = User::whereIn('id', $task->project->members->pluck('user_id'))->get();

                Notification::send($members, new \App\Notifications\TaskComment($task, $comment->created_at));
            }
        }
    }
}
