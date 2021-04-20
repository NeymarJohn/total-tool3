<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailNotificationsColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(1);
        });

        Schema::table('client_details', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_notifications']);
        });
        
        Schema::table('client_details', function (Blueprint $table) {
            $table->dropColumn(['email_notifications']);
        });
    }
}
