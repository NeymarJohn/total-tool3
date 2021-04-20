<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderFieldsInEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('send_reminder', ['yes', 'no'])->default('no')->after('repeat_type');
            $table->integer('remind_time')->nullable()->after('send_reminder');
            $table->enum('remind_type', ['day', 'hour', 'minute'])->default('day')->after('remind_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('send_reminder');
            $table->dropColumn('remind_time');
            $table->dropColumn('remind_type');
        });
    }
}
