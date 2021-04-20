<?php

use App\Project;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['not started', 'in progress', 'on hold', 'canceled', 'finished'])->default('in progress');
        });

        $projects = Project::withTrashed()->get();
        foreach ($projects as $key => $value) {
            if ($value->paused) {
                Project::where('id', $value->id)->update(['status' => 'on hold']);
            }
            if ($value->completion_percent == '100') {
                Project::where('id', $value->id)->update(['status' => 'finished']);
            }
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['paused']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['status']);
            $table->boolean('paused');
        });
    }
}
