<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\MessageSetting;

class CreateMessageSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('allow_client_admin', ['yes', 'no'])->default('no');
            $table->enum('allow_client_employee', ['yes', 'no'])->default('no');
            $table->timestamps();
        });

        $setting = new MessageSetting();
        $setting->allow_client_admin = 'no';
        $setting->allow_client_employee = 'no';
        $setting->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_settings');
    }
}
