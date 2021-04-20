<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDefaultTaskBoardColomnCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::where('default_task_status', 1)->get();
        if ($companies){
            foreach ($companies as $company) {
                $board = \App\TaskboardColumn::where('company_id', $company->id)->first();
                if ($board) {
                    $company->default_task_status = $board->id;
                    $company->save();
                }
            }
        }
        $tasks = \App\Task::where('board_column_id',1)->get();
        if ($tasks) {
            foreach ($tasks as $task) {
                $board = \App\TaskboardColumn::where('company_id', $task->company_id)->first();
                $task->board_column_id = $board->id;
                $task->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
