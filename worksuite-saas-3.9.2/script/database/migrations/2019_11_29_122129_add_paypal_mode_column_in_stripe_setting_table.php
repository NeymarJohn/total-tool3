<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaypalModeColumnInStripeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->enum('paypal_mode', ['sandbox', 'live']);
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
            $table->dropColumn('paypal_mode');
        });
    }
}
