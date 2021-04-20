<?php

namespace App\Observers;

use App\Company;
use App\Events\LeadEvent;
use App\Lead;
use App\Notifications\LeadAgentAssigned;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\Notification;

class LeadObserver
{

    public function saving(Lead $lead)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $lead->company_id = company()->id;
        }
    }

    public function created(Lead $lead)
    {

        if (!isRunningInConsoleOrSeeding()) {

            $company = Company::find($lead->company_id);

            if($company)
            {
                $allAdmins = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.*')
                    ->where('roles.name', 'admin')
                    ->where('users.company_id', $company->id);

                if (request('agent_id') != '') {
                    $allAdmins =  $allAdmins->where('users.id','<>', $lead->lead_agent->id);
                    event(new LeadEvent($lead, $lead->lead_agent, 'LeadAgentAssigned'));
                }

                $allAdmins =  $allAdmins->get();

                Notification::send($allAdmins, new LeadAgentAssigned($lead));
            }
        }
    }

    /**
     * @param Lead $lead
     */
    public function deleting(Lead $lead){
        $universalSearches = UniversalSearch::where('searchable_id', $lead->id)->where('module_type', 'lead')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

}
