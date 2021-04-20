<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TaskboardColumn;

class CreateTaskboardColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskboard_columns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('column_name');
            $table->string('label_color');
            $table->integer('priority');
            $table->timestamps();
        });

        $column = new TaskboardColumn();
        $column->column_name = 'uncategorised';
        $column->label_color = '#b3b3b3';
        $column->priority = '1';
        $column->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taskboard_columns');
    }
}
