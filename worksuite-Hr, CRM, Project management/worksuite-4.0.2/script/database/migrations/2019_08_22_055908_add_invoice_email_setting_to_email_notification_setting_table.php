<?php

use Illuminate\Database\Migrations\Migration;

class AddInvoiceEmailSettingToEmailNotificationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notification = new \App\EmailNotificationSetting();
        $notification->setting_name = 'Invoice Create/Update Notification';
        $notification->send_email   = 'yes';
        $notification->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\EmailNotificationSetting::where('setting_name', 'Invoice Create/Update Notification')->delete();
    }
}
