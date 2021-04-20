<?php

namespace App\Observers;

use App\Discussion;
use App\Events\DiscussionEvent;

class DiscussionObserver
{
    public function saving(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //
        }
    }

    public function created(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding()) {
            event(new DiscussionEvent($discussion));
        }
    }
}
