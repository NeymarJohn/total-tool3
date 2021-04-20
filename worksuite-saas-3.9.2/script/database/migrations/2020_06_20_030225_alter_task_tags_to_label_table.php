<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTaskTagsToLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_tags', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
        });

        Schema::rename('task_tag_list', 'task_label_list');
        Schema::rename('task_tags', 'task_labels');


        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `task_label_list` CHANGE `tag_name` `label_name` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");

        Schema::table('task_label_list', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('color')->nullable()->default(null)->after('label_name');
            $table->string('description')->nullable()->default(null)->after('color');
        });


        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `task_labels` CHANGE `tag_id` `label_id` INT(10) UNSIGNED NOT NULL;");

        Schema::table('task_labels', function (Blueprint $table) {
            $table->foreign('label_id')->references('id')->on('task_label_list')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
