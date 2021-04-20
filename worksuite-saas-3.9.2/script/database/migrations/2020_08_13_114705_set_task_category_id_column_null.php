<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetTaskCategoryIdColumnNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `tasks` DROP FOREIGN KEY `tasks_task_category_id_foreign`;");
        DB::statement("ALTER TABLE `tasks` ADD CONSTRAINT `tasks_task_category_id_foreign` FOREIGN KEY (`task_category_id`) REFERENCES `task_category`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;");
       
        DB::statement("ALTER TABLE `tasks` DROP FOREIGN KEY `tasks_milestone_id_foreign`;");
        DB::statement("ALTER TABLE `tasks` ADD CONSTRAINT `tasks_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;");
       
        DB::statement("ALTER TABLE `leads` CHANGE `company_name` `company_name` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");

         

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
