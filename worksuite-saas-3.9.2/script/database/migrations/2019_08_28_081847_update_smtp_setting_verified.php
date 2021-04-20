<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSmtpSettingVerified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->boolean('verified')->default(0);
        });

        $smtp = \App\SmtpSetting::first();

        if ($smtp && $smtp->mail_driver == 'smtp') {

            $response = $smtp->verifySmtp();
            if ($response['success']){
                $smtp->verified = 1;
            }
            $smtp->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->dropColumn('verified');
        });
    }
}
