<?php

namespace App\Observers;


use App\Notifications\RemovalRequestAdminNotification;
use App\Notifications\RemovalRequestApprovedRejectUser;
use App\RemovalRequest;
use App\User;

class RemovalRequestObserver
{

    public function saving(RemovalRequest $removalRequest)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $removalRequest->company_id = company()->id;
        }
    }

    public function created(RemovalRequest $removalRequest)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $notifyUsers = User::frontAllAdmins(company()->id);
            foreach ($notifyUsers as $notifyUser) {
                $notifyUser->notify(new RemovalRequestAdminNotification());
            }
        }
    }

    public function updated(RemovalRequest $removal)
    {
        if (!isRunningInConsoleOrSeeding()) {
            try {
                if ($removal->user) {
                    $removal->user->notify(new RemovalRequestApprovedRejectUser($removal->status));
                }
            } catch (\Exception $e) {

            }
        }
    }
}
