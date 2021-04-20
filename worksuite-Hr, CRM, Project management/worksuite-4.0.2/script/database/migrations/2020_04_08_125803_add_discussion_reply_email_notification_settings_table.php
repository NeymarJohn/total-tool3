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
       

        $setting = new EmailNotificationSetting();
        $setting->setting_name = 'Discussion Reply';
        $setting->send_email = 'yes';
        $setting->slug = str_slug($setting->setting_name);
        $setting->save();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        EmailNotificationSetting::where('slug', 'discussion-reply')->delete();
    }
}
