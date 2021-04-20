<?php

use App\EmailNotificationSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscussionReplyEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::all();

        if ($companies) {
            foreach ($companies as $company) {
                EmailNotificationSetting::create(
                    [
                        'setting_name' => 'Discussion Reply',
                        'send_email' => 'yes',
                        'company_id' => $company->id,
                    ]
                );
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
        EmailNotificationSetting::where('setting_name', 'Discussion Reply')->delete();
    }
}
