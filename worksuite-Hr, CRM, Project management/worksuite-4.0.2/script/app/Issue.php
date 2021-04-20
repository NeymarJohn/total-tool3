<?php

namespace App;

use App\Observers\IssueObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Issue extends BaseModel
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::observe(IssueObserver::class);
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * @param $projectId
     * @return mixed
     */
    public static function projectIssuesPending($projectId, $userID=null)
    {
        $projectIssue = Issue::where('project_id', $projectId);

        if($userID)
        {
            $projectIssue = $projectIssue->where('user_id', '=', $userID);
        }

        $projectIssue = $projectIssue->where('status', 'pending')
            ->get();

        return $projectIssue;
    }

    public function checkIssueClient() {
        $issue = Issue::where('id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->count();

        if($issue > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

}
