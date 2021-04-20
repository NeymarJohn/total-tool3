<?php

use App\TaskboardColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultTaskStatusColumnOrganisationSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('default_task_status')->unsigned()->nullable();
            $table->foreign('default_task_status')->references('id')->on('taskboard_columns')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['default_task_status']);
            $table->dropColumn(['default_task_status']);
        });
    }
}
