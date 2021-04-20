<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdInRazorpayInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('razorpay_invoices', function (Blueprint $table) {
            $table->string('order_id')->nullable()->default(null)->after('subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('razorpay_invoices', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
