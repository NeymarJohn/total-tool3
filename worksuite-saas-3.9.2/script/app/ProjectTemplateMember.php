<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProjectTemplateMember extends BaseModel
{
    use Notifiable;

    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function projectTemplate() {
        return $this->belongsTo(ProjectTemplate::class);
    }

    public static function byProject($id){
        return ProjectTemplateMember::join('users', 'users.id', '=', 'project_template_members.user_id')
            ->where('project_template_members.project_id', $id)
            ->where('users.status','active')
            ->get();
    }

    public static function checkIsMember($projectId, $userId){
        return ProjectTemplateMember::where('project_template_id', $projectId)
            ->where('user_id', $userId)->first();
    }
}
