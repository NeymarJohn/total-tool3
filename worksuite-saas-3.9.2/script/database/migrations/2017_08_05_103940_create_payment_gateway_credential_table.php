<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentGatewayCredentialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_secret')->nullable();
            $table->timestamps();
        });

        $credential = new \App\PaymentGatewayCredentials();
        $credential->paypal_client_id = null;
        $credential->paypal_secret = null;
        $credential->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway_credentials');
    }
}
