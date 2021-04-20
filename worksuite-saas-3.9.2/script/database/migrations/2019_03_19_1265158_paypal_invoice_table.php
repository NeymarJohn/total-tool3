<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaypalInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('set null');
            $table->double('sub_total', [15,2])->nullable()->default(null);
            $table->double('total', [15,2])->nullable()->default(null);
            $table->string('transaction_id')->nullable()->default(null);
            $table->string('remarks')->nullable()->default(null);
            $table->string('billing_frequency')->nullable()->default(null);
            $table->integer('billing_interval')->nullable()->default(null);
            $table->dateTime('paid_on')->nullable()->default(null);
            $table->dateTime('next_pay_date')->nullable()->default(null);
            $table->enum('recurring', ['yes', 'no'])->nullable()->default('no');
            $table->enum('status', ['paid', 'unpaid', 'pending'])->nullable()->default('pending');
            $table->string('plan_id')->nullable()->default(null);
            $table->string('event_id')->nullable()->default(null);
            $table->dateTime('end_on')->nullable()->default(null);
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
        Schema::dropIfExists('paypal_invoices');
    }
}
