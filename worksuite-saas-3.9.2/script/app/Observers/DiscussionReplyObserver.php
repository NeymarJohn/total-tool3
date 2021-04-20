<?php

namespace App\Observers;

use App\DiscussionReply;
use App\Events\DiscussionReplyEvent;
use Carbon\Carbon;

class DiscussionReplyObserver
{
    public function saving(DiscussionReply $discussionReply)
    {
        if (company()) {
            $discussionReply->company_id = company()->id;
        }
    }

    public function created(DiscussionReply $discussionReply)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $discussion = $discussionReply->discussion;
            $discussion->last_reply_at = Carbon::now()->toDateTimeString();
            $discussion->last_reply_by_id = user()->id;
            $discussion->save();

            if ($discussion->user_id != user()->id) {
                event(new DiscussionReplyEvent($discussionReply, $discussion->user));
            }
        }
    }

    public function deleted(DiscussionReply $discussionReply)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $discussion = $discussionReply->discussion;
            $discussion->best_answer_id = null;
            $discussion->save();
        }
    }
}
