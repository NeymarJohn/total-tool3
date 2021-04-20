<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyStatusColumnInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
//            DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('not started', 'in progress', 'on hold', 'canceled', 'finished','under review')");
            DB::statement("ALTER TABLE `projects` CHANGE `status` `status` ENUM('not started', 'in progress', 'on hold', 'canceled', 'finished','under review') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'in progress';");

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
            //
        });
    }
}
