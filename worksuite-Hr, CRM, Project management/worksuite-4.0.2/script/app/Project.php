<?php

namespace App;

use App\Observers\ProjectObserver;
use App\Traits\CustomFieldsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends BaseModel
{
    use CustomFieldsTrait;
    use SoftDeletes;

    protected $dates = ['start_date', 'deadline'];

    protected $guarded = ['id'];

    protected $appends = ['isProjectAdmin'];

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectObserver::class);

    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function rating()
    {
        return $this->hasOne(ProjectRating::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function clientdetails()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'user_id');
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    public function members_many()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'project_id')->orderBy('id', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'project_id')->orderBy('id', 'desc');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'project_id')->orderBy('id', 'desc');
    }

    public function times()
    {
        return $this->hasMany(ProjectTimeLog::class, 'project_id')->orderBy('id', 'desc');
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class, 'project_id')->orderBy('id', 'desc');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'project_id')->orderBy('id', 'desc');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'project_id')->orderBy('id', 'desc');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'project_id')->orderBy('id', 'desc');
    }

    /**
     * @return bool
     */
    public function checkProjectUser()
    {
        $project = ProjectMember::where('project_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->count();

        if ($project > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function checkProjectClient()
    {
        $project = Project::where('id', $this->id)
            ->where('client_id', auth()->user()->id)
            ->count();

        if ($project > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function clientProjects($clientId)
    {
        return Project::where('client_id', $clientId)->get();
    }

    public static function allProjects()
    {
        return cache()->remember(
            'all-projects', 60*60*24, function () {
                return Project::orderBy('project_name', 'asc')->get();
            }
        );
    }

    public static function byEmployee($employeeId)
    {
        return Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', $employeeId)
            ->get();
    }

    public function scopeCompleted($query)
    {
        return $query->where('completion_percent', '100');
    }

    public function scopeInProcess($query)
    {
        return $query->where('status', 'in progress');
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', 'on hold');
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not started');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    public function scopeOverdue($query)
    {
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
        return $query->where('completion_percent', '<>', '100')
            ->where('deadline', '<', Carbon::today()->timezone($setting->timezone));
    }

    public function getIsProjectAdminAttribute()
    {
        if (auth()->user() && $this->project_admin == auth()->user()->id) {
            return true;
        }
        return false;
    }

    public function pinned()
    {
        $pin = Pinned::where('user_id', user()->id)->where('project_id', $this->id)->first();
        if(!is_null($pin)) {
            return true;
        }
        return false;
    }
}
