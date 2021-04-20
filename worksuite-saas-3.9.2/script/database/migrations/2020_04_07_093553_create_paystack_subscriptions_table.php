<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaystackSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paystack_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('subscription_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('token');
            $table->string('plan_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->string('paystack_monthly_plan_id')->nullable()->after('stripe_monthly_plan_id');
            $table->string('paystack_annual_plan_id')->nullable()->after('paystack_monthly_plan_id');
        });

        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->string('paystack_client_id')->nullable();
            $table->string('paystack_secret')->nullable();
            $table->enum('paystack_status', ['active', 'inactive'])->default('inactive')->nullable();
            $table->string('paystack_merchant_email')->nullable();
            $table->string('paystack_payment_url')->default('https://api.paystack.co')->nullable();
        });

        Schema::create('paystack_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('amount')->nullable();
            $table->date('pay_date')->nullable();
            $table->date('next_pay_date')->nullable();
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
        Schema::dropIfExists('paystack_subscriptions');
    }
}
