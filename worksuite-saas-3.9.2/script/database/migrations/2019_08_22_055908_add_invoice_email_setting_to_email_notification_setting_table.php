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
        $companies = \App\Company::all();


        if($companies){
            \App\EmailNotificationSetting::whereNull('company_id')->delete();
            foreach($companies as $company){
                $notification = new \App\EmailNotificationSetting();

                $notification->company_id   = $company->id;
                $notification->setting_name = 'Invoice Create/Update Notification';
                $notification->send_email   = 'yes';
                $notification->save();
            }
        }
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
