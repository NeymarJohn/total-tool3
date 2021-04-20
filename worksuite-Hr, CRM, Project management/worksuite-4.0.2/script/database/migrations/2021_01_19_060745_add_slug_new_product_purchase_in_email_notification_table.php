<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\EmailNotificationSetting;


class AddSlugNewProductPurchaseInEmailNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_notification_settings', function (Blueprint $table) {
            $settings = EmailNotificationSetting::all();

            foreach ($settings as $setting) {
                if($setting->slug == ''){
                    $setting->slug = str_slug($setting->setting_name);
                    $setting->save();
                }
                
            }
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
            
        });
    }
}
