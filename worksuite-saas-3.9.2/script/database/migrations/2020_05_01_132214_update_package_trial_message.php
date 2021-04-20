<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePackageTrialMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_settings', function (Blueprint $table) {
            $table->text('trial_message')->after('modules')->nullable();
        });

        $setting = \App\PackageSetting::first();
        if(!is_null($setting)){
            $setting->trial_message = 'Start '.$setting->no_of_days.' days free trial';
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_settings', function (Blueprint $table) {
            //
        });
    }
}
