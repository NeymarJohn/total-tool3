<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\EmployeeLeaveQuota;
use App\LeaveType;
use App\UniversalSearch;

class EmployeeDetailObserver
{

    public function saving(EmployeeDetails $detail)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $detail->company_id = company()->id;
        }
    }

    public function created(EmployeeDetails  $detail){
        $leaveTypes = LeaveType::where('company_id', $detail->company_id)->get();
        foreach ($leaveTypes as $key => $value) {
            EmployeeLeaveQuota::create(
                [
                    'company_id' => $detail->company_id,
                    'user_id' => $detail->user_id,
                    'leave_type_id' => $value->id,
                    'no_of_leaves' => $value->no_of_leaves
                ]
            );
        }
    }

    public function deleting(EmployeeDetails $detail){
        $universalSearches = UniversalSearch::where('searchable_id', $detail->user_id)->where('module_type', 'employee')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

}
