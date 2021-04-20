<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends BaseModel
{
    protected $table = "task_history";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function sub_task()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id');
    }

    public function board_column()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }
}
