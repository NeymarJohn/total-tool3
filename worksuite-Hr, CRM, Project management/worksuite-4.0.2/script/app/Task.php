<?php

namespace App;

use App\Observers\TaskObserver;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends BaseModel
{
    use Notifiable;
    use CustomFieldsTrait;

    protected static function boot()
    {
        parent::boot();

        static::observe(TaskObserver::class);
    }
    
    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    protected $dates = ['due_date', 'completed_on', 'start_date'];
    protected $appends = ['due_on', 'create_on'];
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')->withTrashed();
    }

    public function label()
    {
        return $this->hasMany(TaskLabel::class, 'task_id');
    }

    public function board_column()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function labels()
    {
        return $this->belongsToMany(TaskLabelList::class, 'task_labels', 'task_id', 'label_id');
    }

    public function create_by()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function subtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id');
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class, 'task_id')->orderBy('id', 'desc');
    }

    public function completedSubtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id')->where('sub_tasks.status', 'complete');
    }

    public function incompleteSubtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id')->where('sub_tasks.status', 'incomplete');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->orderBy('id', 'desc');
    }
    public function notes()
    {
        return $this->hasMany(TaskNote::class, 'task_id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class, 'task_id');
    }

    public function activeTimer()
    {
        return $this->hasOne(ProjectTimeLog::class, 'task_id')
            ->whereNull('project_time_logs.end_time')
            ->where('project_time_logs.user_id', user()->id);
    }

    public function activeTimerAll()
    {
        return $this->hasMany(ProjectTimeLog::class, 'task_id')
            ->whereNull('project_time_logs.end_time');
    }

    public function timeLogged()
    {
        return $this->hasMany(ProjectTimeLog::class, 'task_id');
    }

    /**
     * @return string
     */
    public function getDueOnAttribute()
    {
        if (!is_null($this->due_date)) {
            return $this->due_date->format('d M, y');
        }
        return "";
    }
    public function getCreateOnAttribute()
    {
        if (!is_null($this->start_date)) {
            return $this->start_date->format('d M, y');
        }
        return "";
    }

    public function getIsTaskUserAttribute()
    {
        if (auth()->user()) {
            return TaskUser::where('task_id', $this->id)->where('user_id', auth()->user()->id)->first();
        }
    }

    public function getTotalEstimatedMinutesAttribute()
    {
        $hours = $this->estimate_hours;
        $minutes = $this->estimate_minutes;
        return ($hours * 60) + $minutes;
    }

    /**
     * @param $projectId
     * @param null $userID
     */
    public static function projectOpenTasks($projectId, $userID = null)
    {
        $taskBoardColumn = TaskboardColumn::completeColumn();
        $projectTask = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('tasks.board_column_id', '<>', $taskBoardColumn->id)->select('tasks.*');

        if ($userID) {
            $projectIssue = $projectTask->where('task_users.user_id', '=', $userID);
        }

        $projectIssue = $projectTask->where('project_id', $projectId)
            ->get();

        return $projectIssue;
    }

    public static function projectCompletedTasks($projectId)
    {
        $taskBoardColumn = TaskboardColumn::completeColumn();
        return Task::where('tasks.board_column_id', $taskBoardColumn->id)
            ->where('project_id', $projectId)
            ->get();
    }

    public static function projectTasks($projectId, $userID = null, $onlyPublic = null)
    {
        $projectTask = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('project_id', $projectId)->select('tasks.*');

        if ($userID) {
            $projectIssue = $projectTask->where('task_users.user_id', '=', $userID);
        }

        if ($onlyPublic != null) {
            $projectIssue = $projectTask->where(
                function ($q) {
                    $q->where('is_private', 0);

                    if (auth()->user()) {
                        $q->orWhere('created_by', auth()->user()->id);
                    }
                }
            );
        }

        $projectIssue = $projectTask->select('tasks.*');
        $projectIssue = $projectTask->orderBy('start_date', 'asc');
        $projectIssue = $projectTask->groupBy('tasks.id');
        $projectIssue = $projectTask->get();

        return $projectIssue;
    }

    /**
     * @return bool
     */
    public function pinned()
    {
        $pin = Pinned::where('user_id', user()->id)->where('task_id', $this->id)->first();
        if(!is_null($pin)) {
            return true;
        }
        return false;
    }
}
