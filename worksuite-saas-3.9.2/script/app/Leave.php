<?php

namespace App;

use App\Observers\LeaveObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Leave extends BaseModel
{
    protected $dates = ['leave_date'];
    protected $appends = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(LeaveObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
    public function getDateAttribute()
    {
        return $this->leave_date->toDateString();
    }

    public function getLeavesTakenCountAttribute()
    {
        $userId = $this->user_id;
        $setting = Setting::first();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date') {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay/2));
        } else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay/2));
        }

    }

    public static function byUser($userId)
    {

        $user = User::withoutGlobalScope('active')->findOrFail($userId);
        $setting = Company::find($user->company_id);

        if ($setting->leaves_start_from == 'joining_date' && isset($user->employee[0])) {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->get();
        } else {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->get();
        }
    }

    public static function byUserCount($userId)
    {
        $setting = Setting::first();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date') {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->get();

            return (count($fullDay) + (count($halfDay)/2));

        } else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->get();

            return (count($fullDay) + (count($halfDay)/2));
        }
    }
}
