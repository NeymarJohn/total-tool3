<?php

namespace App\Listeners;

use App\Events\DiscussionReplyEvent;
use App\Notifications\NewDiscussionReply;
use Illuminate\Support\Facades\Notification;

class DiscussionReplyListener
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
     * @param  DiscussionReplyEvent $event
     * @return void
     */
    public function handle(DiscussionReplyEvent $event)
    {
        Notification::send($event->notifyUser, new NewDiscussionReply($event->discussionReply));
    }
}
