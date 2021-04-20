<?php

namespace App\Observers;

use App\Notification;
use App\Notifications\NewChat;
use App\UniversalSearch;
use App\User;
use App\UserChat;

class NewChatObserver
{
    public function created(UserChat $userChat)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Notify User
            $notifyUser = User::withoutGlobalScope('active')->findOrFail($userChat->user_id);
            $notifyUser->notify(new NewChat($userChat));
        }
    }

    public function deleting(UserChat $userChat)
    {
        Notification::where('type', 'App\Notifications\NewChat')->where('notifiable_id', $userChat->user_id)->delete();
    }
}
