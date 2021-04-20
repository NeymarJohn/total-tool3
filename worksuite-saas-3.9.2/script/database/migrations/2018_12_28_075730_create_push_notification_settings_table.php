<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PushNotificationSetting;

class CreatePushNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('onesignal_app_id')->nullable();
            $table->text('onesignal_rest_api_key')->nullable();
            $table->string('notification_logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });

        $slack = new PushNotificationSetting();
        $slack->onesignal_app_id = null;
        $slack->onesignal_rest_api_key = null;
        $slack->notification_logo = null;
        $slack->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_notification_settings');
    }
}
