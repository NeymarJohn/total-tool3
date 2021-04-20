<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\Events\NewProjectMemberEvent;
use App\ProjectMember;

class ProjectMemberObserver
{
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
        if (!isRunningInConsoleOrSeeding()) {
            event(new NewProjectMemberEvent($member));
        }
    }
}
