<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OnDeleteSetnullTimelog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dropForeign('project_time_logs_project_id_foreign');
            $table->dropIndex('project_time_logs_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dropForeign('project_time_logs_project_id_foreign');
            $table->dropIndex('project_time_logs_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }
}
