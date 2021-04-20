<?php

use App\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageAmountField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `packages` CHANGE `annual_price` `annual_price` DECIMAL(15,2) UNSIGNED NOT NULL DEFAULT '0.00';");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `packages` CHANGE `monthly_price` `monthly_price` DECIMAL(15,2) UNSIGNED NOT NULL DEFAULT '0.00';");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `project_milestones` CHANGE `cost` `cost` DOUBLE(15,2) NOT NULL;");

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
