<?php

namespace App;

use App\Observers\LeaveTypeObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(LeaveTypeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'leave_type_id');
    }

    public function leavesCount()
    {
        return $this->leaves()
            ->selectRaw('leave_type_id, count(*) as count')
            ->groupBy('leave_type_id');
    }

    public static function byUser($userId)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($userId);
        $setting = Company::find($user->company_id);
        if ($setting->leaves_start_from == 'joining_date' && isset($user->employee[0])) {
            return LeaveType::with(['leavesCount' => function ($q) use ($user, $userId) {
                $q->where('leaves.user_id', $userId);
                $q->where('leaves.leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'));
                $q->where('leaves.status', 'approved');
            }])
                ->get();
        } else {
            return LeaveType::with(['leavesCount' => function ($q) use ($user, $userId) {
                $q->where('leaves.user_id', $userId);
                $q->where('leaves.leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'));
                $q->where('leaves.status', 'approved');
            }])
                ->get();
        }
    }
}
