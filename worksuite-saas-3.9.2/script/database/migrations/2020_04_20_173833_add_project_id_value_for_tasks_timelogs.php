<?php

use App\ProjectTimeLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdValueForTasksTimelogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $timelogs = ProjectTimeLog::join('tasks', 'tasks.id', '=', 'project_time_logs.task_id')
            ->whereNotNull('tasks.project_id')
            ->select('project_time_logs.id', 'tasks.project_id')
            ->get();
        
        foreach ($timelogs as $key => $value) {
            $timelog = ProjectTimeLog::find($value->id);
            $timelog->project_id = $value->project_id;
            $timelog->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
