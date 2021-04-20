<?php

namespace App;

use App\Observers\ProjectTemplateObserver;
use App\Scopes\CompanyScope;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends BaseModel
{
    use CustomFieldsTrait;

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectTemplateObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes(['active']);
    }

    public function members()
    {
        return $this->hasMany(ProjectTemplateMember::class );
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTemplateTask::class, 'project_template_id')->orderBy('id', 'desc');
    }


    /**
     * @return bool
     */
    public function checkProjectUser()
    {
        $project = ProjectTemplateMember::where('project_template_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->count();

        if($project > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public function checkProjectClient()
    {
        $project = ProjectTemplateMember::where('id', $this->id)
            ->where('client_id', auth()->user()->id)
            ->count();

        if($project > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public static function clientProjects($clientId) {
        return ProjectTemplateMember::where('client_id', $clientId)->get();
    }

    public static function byEmployee($employeeId) {
        return ProjectTemplateMember::join('project_template_members', 'project_template_members.project_template_id', '=', 'project_templates.id')
            ->where('project_template_members.user_id', $employeeId)
            ->get();
    }

}
