<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 13/07/17
 * Time: 4:53 PM
 */

namespace App\Traits;

use App\Project;
use App\Task;
use App\TaskboardColumn;

trait ProjectProgress{

    public function calculateProjectProgress($projectId){
        if(is_null($projectId)){
            return;
        }

        $totalTasks = Task::where('project_id', $projectId)->count();

        if($totalTasks == 0){
            return "0";
        }
        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();

        $completedTasks = Task::where('project_id', $projectId)
            ->where('tasks.board_column_id', $taskBoardColumn->id)
            ->count();

        $percentComplete = ($completedTasks/$totalTasks)*100;

        $project = Project::findOrFail($projectId);
        $project->completion_percent = $percentComplete;

        $project->save();

        return $percentComplete;

    }

}