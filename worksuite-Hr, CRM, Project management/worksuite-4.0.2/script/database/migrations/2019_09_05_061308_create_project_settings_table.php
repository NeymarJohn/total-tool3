<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('project_settings');
        Schema::create('project_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('send_reminder', ['yes', 'no']);
            $table->integer('remind_time');
            $table->string('remind_type');
            $table->string('remind_to')->default(json_encode(['admins', 'members']));
            $table->timestamps();
        });

        $project_setting = new \App\ProjectSetting();

        $project_setting->send_reminder = 'no';
        $project_setting->remind_time = 5;
        $project_setting->remind_type = 'days';

        $project_setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_settings');
    }
}
