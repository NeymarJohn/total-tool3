<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name');

            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('project_category')->onDelete('set null')->onUpdate('cascade');

            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->mediumText('project_summary')->nullable();
            $table->longText('notes')->nullable();
            $table->mediumText('feedback')->nullable();
            $table->enum('client_view_task',['enable','disable'])->default('disable');
            $table->enum('allow_client_notification',['enable','disable'])->default('disable');
            $table->enum('manual_timelog',['enable','disable'])->default('disable');

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
        Schema::dropIfExists('project_templates');
    }
}
