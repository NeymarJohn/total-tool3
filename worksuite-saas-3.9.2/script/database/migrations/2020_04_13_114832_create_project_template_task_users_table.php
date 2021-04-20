<?php

use App\ProjectTemplateTask;
use App\ProjectTemplateTaskUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTemplateTaskUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_template_task_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('project_template_task_id')->unsigned();
            $table->foreign('project_template_task_id')->references('id')->on('project_template_tasks')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        $allTasks = ProjectTemplateTask::select('id', 'user_id')->get();

        foreach ($allTasks as $key => $value) {
            ProjectTemplateTaskUser::create(
                [
                    'project_template_task_id' => $value->id,
                    'user_id' => $value->user_id
                ]
            );
        }


        Schema::table('project_template_tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_template_task_users');
    }
}
