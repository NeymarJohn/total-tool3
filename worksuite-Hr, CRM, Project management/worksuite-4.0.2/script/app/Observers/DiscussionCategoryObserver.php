<?php

namespace App\Observers;

use App\DiscussionCategory;
use App\Events\NewUserEvent;
use App\User;

class DiscussionCategoryObserver
{
    public function saving(DiscussionCategory $discussionCategory)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //
        }
    }
}
