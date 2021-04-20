<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastCronRunInOrganisationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->timestamp('last_cron_run')->nullable()->default(null);
        });
        $globalSetting = \App\Setting::first();
        if ($globalSetting) {
            $globalSetting->last_cron_run = \Carbon\Carbon::now();
            $globalSetting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->dropColumn('last_cron_run');
        });
    }
}
