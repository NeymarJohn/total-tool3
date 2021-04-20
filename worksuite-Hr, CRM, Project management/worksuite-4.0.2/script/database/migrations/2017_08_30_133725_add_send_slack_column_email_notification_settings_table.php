<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendSlackColumnEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_notification_settings', function (Blueprint $table) {
            $table->enum('send_slack', ['yes', 'no'])->default('no')->after('send_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_notification_settings', function (Blueprint $table) {
            $table->dropColumn(['send_slack']);
        });
    }
}
