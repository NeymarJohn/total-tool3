<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\Events\NewProjectMemberEvent;
use App\Notifications\NewProjectMember;
use App\ProjectMember;

class ProjectMemberObserver
{

    public function saving(ProjectMember $member)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $member->company_id = company()->id;
        }
    }

    public function creating(ProjectMember $projectMember)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $member = EmployeeDetails::where('user_id', $projectMember->user_id)->first();
            if (!is_null($member)) {
                $projectMember->hourly_rate = (!is_null($member->hourly_rate) ? $member->hourly_rate : 0);
            }
        }
    }

    public function created(ProjectMember $member)
    {
        if (!app()->runningInConsole() ) {
            event(new NewProjectMemberEvent($member));
        }
    }
}
