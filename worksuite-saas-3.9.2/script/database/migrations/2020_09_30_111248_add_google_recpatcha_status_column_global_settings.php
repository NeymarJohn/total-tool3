<?php

use App\GlobalSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleRecpatchaStatusColumnGlobalSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->boolean('google_recaptcha_status')->default(false);
        });

        $settings = GlobalSetting::first();
        if($settings){
            if ($settings->google_recaptcha_key != "" && $settings->google_recaptcha_secret != "") {
                $settings->google_recaptcha_status = 1;
                $settings->save();
            }
        }
        session()->forget('global_settings');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn(['google_recaptcha_status']);
        });
    }
}
