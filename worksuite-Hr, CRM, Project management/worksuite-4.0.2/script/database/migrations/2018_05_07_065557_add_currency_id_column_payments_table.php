<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Invoice;
use App\Payment;

class AddCurrencyIdColumnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->nullable()->after('transaction_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
        });

        $invoices = Invoice::where('status', 'paid')->get();
        foreach ($invoices as $invoice){
            $payment = Payment::where("invoice_id", $invoice->id)->first();
            $payment->currency_id = $invoice->currency_id;
            $payment->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['currency_id']);
        });
    }
}
