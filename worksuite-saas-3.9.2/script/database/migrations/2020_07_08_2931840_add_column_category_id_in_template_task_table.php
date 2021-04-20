<?php

use App\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCategoryIdInTemplateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_template_tasks', function (Blueprint $table) {
            $table->unsignedInteger('project_template_task_category_id')->nullable()->default(null);
            $table->foreign('project_template_task_category_id')->references('id')->on('task_category')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_template_tasks', function (Blueprint $table) {
            $table->dropForeign('project_template_tasks_project_template_task_category_id_foreign');
            $table->dropColumn(['project_template_task_category_id']);
        });
    }
}
