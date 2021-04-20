<?php

namespace App\Observers;

use App\Events\LeadEvent;
use App\Lead;
use App\Notifications\LeadAgentAssigned;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\Notification;

class LeadObserver
{

    public function updated(Lead $lead)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($lead->isDirty('agent_id')) {
                event(new LeadEvent($lead, $lead->lead_agent, 'LeadAgentAssigned'));
            }
        }
    }
    public function created(Lead $lead)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (request('agent_id') != '') {
                event(new LeadEvent($lead, $lead->lead_agent, 'LeadAgentAssigned'));
            } else {
                Notification::send(User::allAdmins(), new LeadAgentAssigned($lead));
            }
        }
    }

    public function deleted(Lead $lead)
    {
        UniversalSearch::where('searchable_id', $lead->id)->where('module_type', 'lead')->delete();
    }
}
