<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TaskboardColumn;
use App\Task;

class ChangeDefaultTaskboardColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('taskboard_columns', function (Blueprint $table){
           $table->string('column_name')->unique()->change();
        });

        $uncatColumn = TaskboardColumn::find(1);
        $uncatColumn->column_name = 'Incomplete';
        $uncatColumn->label_color = '#d21010';
        $uncatColumn->save();

        $maxPriority = TaskboardColumn::max('priority');

        $completeColumn = new TaskboardColumn();
        $completeColumn->column_name = 'Completed';
        $completeColumn->label_color = '#679c0d';
        $completeColumn->priority = ($maxPriority+1);
        $completeColumn->save();

        $tasks = Task::where('board_column_id', 1)
            ->orWhereNull('board_column_id')
            ->get();

        foreach ($tasks as $task){
            if($task->status == 'completed'){
                $task->board_column_id = $completeColumn->id;
            }
            else{
                $task->board_column_id = 1;
            }
            $task->save();
        }

        $oldPosition = $completeColumn->priority;
        $newPosition = 2;

        $otherColumns = TaskboardColumn::where('priority', '<', $completeColumn->priority)
            ->where('priority', '<>', 1)
            ->orderBy('priority', 'asc')
            ->get();

        foreach($otherColumns as $column){
            $pos = TaskboardColumn::where('priority', $column->priority)->first();
            $pos->priority = ($pos->priority+1);
            $pos->save();
        }

        $completeColumn->priority = 2;
        $completeColumn->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taskboard_columns', function (Blueprint $table){
            $table->dropUnique(['column_name']);
        });

        $uncatColumn = TaskboardColumn::find(1);
        $uncatColumn->column_name = 'uncategorised';
        $uncatColumn->label_color = '#b3b3b3';
        $uncatColumn->save();

        $completeColumn = TaskboardColumn::where('column_name', 'completed')->first();

        if(!is_null($completeColumn)){
            $otherColumns = TaskboardColumn::where('priority', '>', $completeColumn->priority)
                ->orderBy('priority', 'asc')
                ->get();

            foreach($otherColumns as $column){
                $pos = TaskboardColumn::where('priority', $column->priority)->first();
                $pos->priority = ($pos->priority-1);
                $pos->save();
            }
            $completeColumn->delete();
        }


    }
}
