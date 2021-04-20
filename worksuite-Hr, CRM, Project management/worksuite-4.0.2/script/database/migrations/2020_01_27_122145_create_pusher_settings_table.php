<?php

use App\PusherSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePusherSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pusher_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pusher_app_id')->nullable();
            $table->string('pusher_app_key')->nullable();
            $table->string('pusher_app_secret')->nullable();
            $table->string('pusher_cluster')->nullable();
            $table->boolean('force_tls');
            $table->boolean('status');
            $table->timestamps();
        });

        $pusherSetting = new PusherSetting();
        $pusherSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pusher_settings');
    }
}
