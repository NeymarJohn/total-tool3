<?php

namespace App\Listeners;

use App\Events\NewIssueEvent;
use App\Notifications\NewIssue;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewIssueListener
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
     * @param  NewIssueEvent  $event
     * @return void
     */
    public function handle(NewIssueEvent $event)
    {
        Notification::send(User::allAdmins(), new NewIssue($event->issue));
    }
}
