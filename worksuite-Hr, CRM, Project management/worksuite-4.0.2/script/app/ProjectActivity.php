<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends BaseModel
{
    protected $table = 'project_activity';

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public static function getProjectActivities($projectId, $limit, $userID = null)
    {
        $projectActivity = ProjectActivity::select('project_activity.id', 'project_activity.project_id', 'project_activity.activity', 'project_activity.created_at', 'project_activity.updated_at');

        if ($userID) {
            $projectActivity = $projectActivity->join('projects', 'projects.id', '=', 'project_activity.project_id')
                ->join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->where('project_members.user_id', '=', $userID);
        }

        $projectActivity = $projectActivity->where('project_activity.project_id', $projectId)
            ->orderBy('project_activity.id', 'desc')
            ->limit($limit)
            ->get();

        return $projectActivity;
    }
}
