<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_invoice_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('offline_payment_methods')->onDelete('cascade')->onUpdate('cascade');

            $table->string('slip');
            $table->longText('description');
            $table->enum('status', ['pending', 'approve', 'reject']);

            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `invoices` CHANGE `status` `status` ENUM('paid','unpaid','partial','review') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_invoice_payments');
    }
}
