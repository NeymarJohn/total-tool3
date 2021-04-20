<?php

namespace App\Observers;

use App\Events\RemovalRequestAdminEvent;
use App\Events\RemovalRequestApproveRejectEvent;
use App\RemovalRequest;

class RemovalRequestObserver
{
    public function created(RemovalRequest $removalRequest)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new RemovalRequestAdminEvent($removalRequest));
        }
    }

    public function updated(RemovalRequest $removal)
    {
        if (!isRunningInConsoleOrSeeding()) {
            try {
                if ($removal->user) {
                    event(new RemovalRequestApproveRejectEvent($removal));
                }
            } catch (\Exception $e) {

            }
        }
    }
}
