<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCompanyIdColumnSmtpSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id']);
        });

        $smtp = new \App\SmtpSetting();
        $smtp->mail_driver = 'mail';
        $smtp->mail_host = 'smtp.gmail.com';
        $smtp->mail_port = '587';
        $smtp->mail_username = 'myemail@gmail.com';
        $smtp->mail_password = 'mypassword';
        $smtp->mail_from_name = 'froiden';
        $smtp->mail_encryption = 'tls';
        $smtp->save();
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
