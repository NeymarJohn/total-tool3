<?php

namespace App\Observers;

use App\Events\RemovalRequestAdminLeadEvent;
use App\Events\RemovalRequestApprovedRejectLeadEvent;
use App\RemovalRequestLead;

class RemovalRequestLeadObserver
{
    public function created(RemovalRequestLead $removalRequestLead)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new RemovalRequestAdminLeadEvent($removalRequestLead));
        }
    }

    public function updated(RemovalRequestLead $removal)
    {
        if (!isRunningInConsoleOrSeeding()) {
            try {
                if ($removal->lead) {
                    event(new RemovalRequestApprovedRejectLeadEvent($removal));
                }
            } catch (\Exception $e) {

            }
        }
    }
}
