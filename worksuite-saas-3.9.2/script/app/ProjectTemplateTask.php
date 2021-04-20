<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProjectTemplateTask extends BaseModel
{
    use Notifiable;

    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    public function projectTemplate(){
        return $this->belongsTo(ProjectTemplate::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function users()
    {
        return $this->hasMany(ProjectTemplateTaskUser::class, 'project_template_task_id');
    }

    public function users_many()
    {
        return $this->belongsToMany(User::class, 'project_template_task_users');
    }

    public function subtasks()
    {
        return $this->hasMany(ProjectTemplateSubTask::class);
    }
}
