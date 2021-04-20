<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slack_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('slack_webhook')->nullable();
            $table->string('slack_logo')->nullable();
            $table->timestamps();
        });

        $slack = new \App\SlackSetting();
        $slack->slack_webhook = null;
        $slack->slack_logo = null;
        $slack->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slack_settings');
    }
}
