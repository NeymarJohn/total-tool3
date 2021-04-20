<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveQuota extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

}
