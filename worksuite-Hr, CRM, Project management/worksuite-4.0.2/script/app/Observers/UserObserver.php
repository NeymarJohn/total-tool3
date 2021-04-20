<?php

namespace App\Observers;

use App\Events\NewUserEvent;
use App\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function created(User $user)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $sendMail = true;
            if (request()->has('sendMail') && request()->sendMail == 'no') {
                $sendMail = false;
            }

            if ($sendMail && request()->has('password')) {
                event(new NewUserEvent($user, request()->password));
            }
        }
    }
    public function deleted(User $user)
    {
        if (!isRunningInConsoleOrSeeding()) {
            \cache()->forget('all-clients');
            \cache()->forget('all-employees');
            \cache()->forget('all-admins');
        }
    }
}
