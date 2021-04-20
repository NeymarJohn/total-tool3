<?php

namespace App\Observers;

use App\Events\NewIssueEvent;
use App\Issue;
use App\Notifications\NewIssue;
use App\User;
use Illuminate\Support\Facades\Notification;

class IssueObserver
{
    public function created(Issue $issue)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new NewIssueEvent($issue));
        }
    }
}
