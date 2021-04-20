<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('board_column_id')->unsigned()->nullable()->after('status')->default(1);
            $table->foreign('board_column_id')->references('id')->on('taskboard_columns')->onDelete('SET NULL')->onUpdate('cascade');
            $table->integer('column_priority')->after('board_column_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['column_priority']);
            $table->dropForeign(['board_column_id']);
            $table->dropColumn(['board_column_id']);
        });
    }
}
