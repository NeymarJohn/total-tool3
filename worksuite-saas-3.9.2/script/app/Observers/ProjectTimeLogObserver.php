<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\ProjectMember;
use App\ProjectTimeLog;

class ProjectTimeLogObserver
{

    public function saving(ProjectTimeLog $projectTimeLog)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $projectTimeLog->company_id = company()->id;

            $userId = (request()->has('user_id') ? request('user_id') : $projectTimeLog->user_id);
            $projectId = request('project_id');
            
            $timeLogSetting = time_log_setting();
            if ($timeLogSetting->approval_required) {
                $projectTimeLog->approved = 0;
            }

            if ($projectId != "") {
                $member = ProjectMember::where('user_id', $userId)->where('project_id', $projectId)->first();
                $projectTimeLog->hourly_rate = (!is_null($member->hourly_rate) ? $member->hourly_rate : 0);
            } else {
                $task = $projectTimeLog->task;
                if (!is_null($task->project_id)) {
                    $projectId = $task->project_id;
                }
                $member = EmployeeDetails::where('user_id', $userId)->first();

                $projectTimeLog->hourly_rate = (!is_null($member) && !is_null($member->hourly_rate)  ? $member->hourly_rate : 0);
            }
                            
            $hours = intdiv($projectTimeLog->total_minutes, 60);
            $minuteRate = $projectTimeLog->hourly_rate / 60;
            $earning = round($projectTimeLog->total_minutes * $minuteRate);

            $projectTimeLog->earnings = $earning;
        }
    }

}
