<?php

namespace App\Observers;

use App\Lead;
use App\LeadStatus;

class LeadStatusObserver
{

    public function deleting(LeadStatus $leadStatus)
    {
        $defaultStatus = LeadStatus::where('default', 1)->first();
        if ($defaultStatus->id == $leadStatus->id) {
            abort(403);
        }
        Lead::where('status_id', $leadStatus->id)->update(['status_id' => $defaultStatus->id]);;
    }
}
