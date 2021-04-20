<?php

namespace App\Listeners;

use App\Events\NewProjectMemberEvent;
use App\Notifications\NewProjectMember;
use Illuminate\Support\Facades\Notification;

class NewProjectMemberListener
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
     * @param  NewProjectMemberEvent $event
     * @return void
     */
    public function handle(NewProjectMemberEvent $event)
    {
        Notification::send($event->projectMember->user, new NewProjectMember($event->projectMember));
    }
}
