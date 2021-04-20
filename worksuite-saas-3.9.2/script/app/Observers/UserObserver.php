<?php

namespace App\Observers;

use App\Notifications\NewUser;
use App\User;
use Illuminate\Support\Facades\File;


class UserObserver
{
    public function created(User $user)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $sendMail = true;
            if (request()->has('sendMail') && request()->sendMail == 'no') {
                $sendMail = false;
            }

            if ($sendMail && request()->has('password') && \user()) {
                $user->notify(new NewUser(request()->password));
            }
        }
    }

    public function saving(User $user)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (!User::isClient($user->id)) {
            if (request()->has('company_id')) {
                session()->forget('company');
                $user->company_id = request()->company_id;
            } elseif (company()) {
                $user->company_id = company()->id;
            }    
        }
    }

    public function updating(User $user)
    {
        $original = $user->getOriginal();
        if ($user->isDirty('image')) {
            File::delete('user-uploads/avatar/' . $original['image']);
        }
    }

    public function deleting(User $user)
    {
        File::delete('user-uploads/avatar/' . $user->image);
    }
}
