<?php

use App\Invoice;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDueAmountColumnInInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->float('due_amount')->default(0);
        });

        $invoices = Invoice::get();
        foreach ($invoices as $key => $invoice) {
            $invoice->due_amount = $invoice->amountDue();
            $invoice->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('due_amount');
        });
    }
}
