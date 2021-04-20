<?php

namespace App\Events;

use App\DiscussionReply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscussionReplyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussionReply;
    public $notifyUser;
    public $notificationName;

    public function __construct(DiscussionReply $discussionReply, $notifyUser)
    {
        $this->discussionReply = $discussionReply;
        $this->notifyUser = $notifyUser;
    }

}
