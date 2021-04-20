<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInPaymentGatewayCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('paystack_client_id')->nullable();
            $table->string('paystack_secret')->nullable();
            $table->enum('paystack_status', ['active', 'inactive'])->default('inactive')->nullable();
            $table->string('paystack_merchant_email')->nullable();
            $table->string('paystack_payment_url')->default('https://api.paystack.co')->nullable();
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
            //
        });
    }
}
