<?php

namespace App\Observers;

use App\Events\NewProjectEvent;
use App\Project;
use App\UniversalSearch;

class ProjectObserver
{

    public function created(Project $project)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //Send notification to user
            if ($project->client_id != null) {
                event(new NewProjectEvent($project));
            }
        }
    }

    public function deleting(Project $project){
        $universalSearches = UniversalSearch::where('searchable_id', $project->id)->where('module_type', 'project')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

}
