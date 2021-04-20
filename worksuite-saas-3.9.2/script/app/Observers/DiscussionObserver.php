<?php

namespace App\Observers;

use App\Discussion;
use App\Events\DiscussionEvent;
use App\Events\NewUserEvent;
use App\User;

class DiscussionObserver
{
    public function saving(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (company()) {
                $discussion->company_id = company()->id;
            }
        }
    }

    public function created(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding()) {
            event(new DiscussionEvent($discussion));
        }
    }
}
