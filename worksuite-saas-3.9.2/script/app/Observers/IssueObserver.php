<?php

namespace App\Observers;

use App\Issue;
use App\Notifications\NewIssue;
use App\User;
use Illuminate\Support\Facades\Notification;

class IssueObserver
{
    public function created(Issue $issue)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Notify admins
            $admins = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

            Notification::send($admins, new NewIssue($issue));
        }
    }
}
