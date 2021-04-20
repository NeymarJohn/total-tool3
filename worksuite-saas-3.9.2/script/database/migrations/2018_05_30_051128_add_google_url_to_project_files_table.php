<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddGoogleUrlToProjectFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `project_files` CHANGE `hashname` `hashname` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `project_files` CHANGE `size` `size` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
        Schema::table('project_files', function (Blueprint $table)
        {
            $table->string('google_url')
                ->nullable()
                ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `project_files` CHANGE `hashname` `hashname` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
        DB::statement('ALTER TABLE `project_files` CHANGE `size` `size` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
        Schema::table('project_files', function(Blueprint $table){
            $table->dropColumn('google_url');
        });
    }
}
