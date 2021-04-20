<?php

namespace App\Events;

use App\UserChat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userChat;

    public function __construct(UserChat $userChat)
    {
        $this->userChat = $userChat;
    }

}
