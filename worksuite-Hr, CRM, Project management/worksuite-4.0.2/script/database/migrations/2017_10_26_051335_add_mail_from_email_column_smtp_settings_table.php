<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailFromEmailColumnSmtpSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smtp_settings', function (Blueprint $table){
            $table->string('mail_from_email')->default('from@email.com')->after('mail_from_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smtp_settings', function (Blueprint $table){
            $table->dropColumn(['mail_from_email']);
        });
    }
}
