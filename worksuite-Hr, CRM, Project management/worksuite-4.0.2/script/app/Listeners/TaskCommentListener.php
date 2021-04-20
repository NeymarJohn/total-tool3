<?php

namespace App\Listeners;

use App\Events\TaskCommentEvent;
use App\Notifications\TaskComment;
use App\Notifications\TaskCommentClient;
use Illuminate\Support\Facades\Notification;

class TaskCommentListener
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
    public function handle(TaskCommentEvent $event)
    {
        if($event->client == 'client'){
            Notification::send($event->notifyUser, new TaskCommentClient($event->task, $event->created_at));
        }
        else{
            Notification::send($event->notifyUser, new TaskComment($event->task, $event->created_at));
        }
    }
}
