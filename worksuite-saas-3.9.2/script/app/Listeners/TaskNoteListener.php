<?php

namespace App\Listeners;

use App\Events\TaskCommentEvent;
use App\Events\TaskNoteEvent;
use App\Notifications\TaskComment;
use App\Notifications\TaskCommentClient;
use App\Notifications\TaskNote;
use App\Notifications\TaskNoteClient;
use Illuminate\Support\Facades\Notification;

class TaskNoteListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskCommentEvent $event
     * @return void
     */
    public function handle(TaskNoteEvent $event)
    {
        if($event->client == 'client'){
            Notification::send($event->notifyUser, new TaskNoteClient($event->task, $event->created_at));
        }
        else{
            Notification::send($event->notifyUser, new TaskNote($event->task, $event->created_at));
        }
    }
}
