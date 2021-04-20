<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTemplateSubTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_template_sub_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_template_task_id')->unsigned();
            $table->foreign('project_template_task_id')->references('id')->on('project_template_tasks')->onDelete('cascade')->onUpdate('cascade');
            $table->text('title');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->enum('status', ['incomplete', 'complete'])->default('incomplete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_template_sub_task');
    }
}
