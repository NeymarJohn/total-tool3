<?php

namespace App\Observers;

use App\Events\NewChatEvent;
use App\UserChat;

class NewChatObserver
{
    public function created(UserChat $userChat)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new NewChatEvent($userChat));
        }
    }
}
