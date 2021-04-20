<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStipeColumnToPaymentCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('stripe_client_id')->nullable()->default(null)->after('paypal_status');
            $table->string('stripe_secret')->nullable()->default(null)->after('stripe_client_id');
            $table->string('stripe_webhook_secret')->nullable()->default(null)->after('stripe_secret');
            $table->enum('stripe_status', ['active', 'deactive'])->default('active')->after('stripe_webhook_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->dropColumn(['stripe_client_id', 'stripe_secret', 'stripe_status', 'stripe_webhook_secret']);
        });
    }
}
