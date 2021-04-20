<?php

use App\TaskboardColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DefaultTaskStatusColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `companies` CHANGE `default_task_status` `default_task_status` INT UNSIGNED NULL DEFAULT NULL;
        ');
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['default_task_status']);
            $table->foreign('default_task_status')->references('id')->on('taskboard_columns')->onDelete('SET NUll')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
