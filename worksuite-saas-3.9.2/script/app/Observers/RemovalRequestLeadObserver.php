<?php

namespace App\Observers;

use App\Notifications\RemovalRequestAdminNotification;
use App\Notifications\RemovalRequestApprovedRejectLead;
use App\RemovalRequestLead;
use App\User;

class RemovalRequestLeadObserver
{

    public function saving(RemovalRequestLead $removalRequestLead)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $removalRequestLead->company_id = company()->id;
        }
    }

    public function created(RemovalRequestLead $removalRequestLead)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $notifyUsers = User::frontAllAdmins(company()->id);
            foreach ($notifyUsers as $notifyUser) {
                $notifyUser->notify(new RemovalRequestAdminNotification());
            }
        }
    }

    public function updated(RemovalRequestLead $removal)
    {
        if (!isRunningInConsoleOrSeeding()) {
            try {
                if ($removal->lead) {
                    $removal->lead->notify(new RemovalRequestApprovedRejectLead($removal->status));
                }
            } catch (\Exception $e) {

            }
        }
    }
}
