<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteGlobalCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_currencies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('paypal_invoices', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });

        $globalSetting = \App\GlobalSetting::first();

        $paypalInvoices = \App\PaypalInvoice::all();

        if($paypalInvoices){
            foreach ($paypalInvoices as $invoice){
                $invoice->currency_id = $globalSetting->currency_id;
                $invoice->save();
            }
        }

        $razorpayInvoices = \App\RazorpayInvoice::all();

        if($razorpayInvoices){
            foreach ($razorpayInvoices as $invoiceRazor){
                $invoiceRazor->currency_id = $globalSetting->currency_id;
                $invoiceRazor->save();
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       //
    }
}
