<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRazorpaySubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('razorpay_subscriptions', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('subscription_id')->nullable()->default(null);
            $table->string('customer_id')->nullable()->default(null);
            $table->string('name');
            $table->string('razorpay_id');
            $table->string('razorpay_plan');
            $table->integer('quantity');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('razorpay_subscriptions');
    }
}
