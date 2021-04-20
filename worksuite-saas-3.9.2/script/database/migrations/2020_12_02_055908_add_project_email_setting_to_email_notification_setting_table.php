<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectEmailSettingToEmailNotificationSettingTable extends Migration
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
            foreach($companies as $company){
                // When new expense added by admin
                \App\EmailNotificationSetting::create([
                    'setting_name' => 'New Project/Added by Admin',
                    'send_email' => 'yes',
                    'company_id' => $company->id
                ]);

                // When new expense added by member
                \App\EmailNotificationSetting::create([
                    'setting_name' => 'New Project/Added by Member',
                    'send_email' => 'yes',
                    'company_id' => $company->id
                ]);

                // When expense status changed
                \App\EmailNotificationSetting::create([
                    'setting_name' => 'Project File Added',
                    'send_email' => 'yes',
                    'company_id' => $company->id
                ]);

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
        \App\EmailNotificationSetting::where('setting_name', 'New Project/Added by Admin')->delete();
        \App\EmailNotificationSetting::where('setting_name', 'New Project/Added by Member')->delete();
        \App\EmailNotificationSetting::where('setting_name', 'Project File Added')->delete();
    }
}
