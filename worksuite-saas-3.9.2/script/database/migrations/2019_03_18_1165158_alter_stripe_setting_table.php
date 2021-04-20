<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStripeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->string('paypal_client_id')->nullable()->default(null)->after('webhook_key');
            $table->string('paypal_secret')->nullable()->default(null)->after('paypal_client_id');
            $table->enum('paypal_status', ['active', 'inactive'])->default('inactive')->after('paypal_secret');
            $table->enum('stripe_status', ['active', 'inactive'])->default('inactive')->after('paypal_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->dropColumn('paypal_client_id');
            $table->dropColumn('paypal_secret');
            $table->dropColumn('paypal_status');
            $table->dropColumn('stripe_status');
        });
    }
}
