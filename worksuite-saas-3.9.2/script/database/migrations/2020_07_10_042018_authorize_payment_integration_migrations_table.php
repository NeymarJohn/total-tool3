<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthorizePaymentIntegrationMigrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('authorize_api_login_id')->nullable();
            $table->string('authorize_transaction_key')->nullable();
            $table->string('authorize_environment')->nullable();
            $table->enum('authorize_status', ['active', 'inactive'])->default('inactive');
        });

        Schema::table('users', function ($table) {
            $table->string('authorize_id')->nullable();
            $table->string('authorize_payment_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
        });

        Schema::create('authorize_subscriptions', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('subscription_id');
            $table->unsignedInteger('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('plan_type')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->string('authorize_api_login_id')->nullable();
            $table->string('authorize_transaction_key')->nullable();
            $table->string('authorize_signature_key')->nullable();
            $table->string('authorize_environment')->nullable();
            $table->enum('authorize_status', ['active', 'inactive'])->default('inactive');
        });

        Schema::create('authorize_invoices', function (Blueprint $table) {
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
        Schema::table('stripe_setting', function (Blueprint $table) {
            $table->dropColumn('authorize_api_login_id');
            $table->dropColumn('authorize_transaction_key');
            $table->dropColumn('authorize_signature_key');
            $table->dropColumn('authorize_environment');
            $table->dropColumn('authorize_status');
        });

        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->dropColumn('authorize_api_login_id');
            $table->dropColumn('authorize_transaction_key');
            $table->dropColumn('authorize_environment');
            $table->dropColumn('authorize_status');
        });

        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->dropColumn('authorize_id');
            $table->dropColumn('authorize_payment_id');
            $table->dropColumn('card_brand');
            $table->dropColumn('card_last_four');
        });

        Schema::drop('authorize_subscriptions');
        Schema::drop('authorize_invoices');
    }
}
